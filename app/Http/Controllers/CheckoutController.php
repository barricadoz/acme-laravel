<?php

namespace App\Http\Controllers;

use App\Http\Classes\Cart;
use App\Product;
use App\Order;
use App\OrderDetail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Stripe\Customer;
use Stripe\Stripe;

class CheckoutController extends Controller {

    private $emptyCartMessage = 'Your basket is currently empty';

    private $unableToCompleteOrderMessage = 'Sorry - there was a problem processing your order and it could not be completed at this time';

    private $stripeCheckoutCompleteLabel = 'checkout.session.completed';

    private $successPath = '/checkout/complete';

    private $failurePath = '/checkout/failed';

    private $secretKey;

    private $publishableKey;

    public function __construct() {
        $this->secretKey = getenv('STRIPE_SECRET');
        $this->publishableKey = getenv('STRIPE_PUBLISHER_KEY');
    }

    public function confirmOrder() {
        // Redirect to the basket page if the basket is empty.
        if (!Session::has('user_cart') || count(Session::get('user_cart')) < 1) {
            return redirect('/basket');
        }

        $items = [];
        $cartTotal = 0;
        $userCart = Session::get('user_cart');

        foreach ($userCart as $cart_item) {
            $productId = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $item = Product::where('id', $productId)->first();

            if (!$item) {
                continue;
            }

            $itemTotal = $item->price * $quantity;
            $cartTotal = $itemTotal + $cartTotal;
            $itemTotal = number_format($itemTotal, 2);
            array_push($items, [
                'id' => $item->id,
                'name' => $item->name,
                'image' => $item->image_path,
                'description' => $item->description,
                'price' => $item->price,
                'quantity' => $quantity,
                'total' => $itemTotal,
            ]);
        }

        return view('purchase.confirm')->with([
            'items' => $items,
            'cartTotal' => $cartTotal,
        ]);
    }

    public function checkout() {
        // If the referrer is not the confirm order page, redirect to that page.
        if (!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] !== getenv('APP_URL') . '/checkout/confirm-order') {
            return redirect('/checkout/confirm-order');
        }

        $stripeLineItems = [];
        $orderNumber = uniqid();
        Session::put('order_number', $orderNumber);

        $userId = Auth::check() ? Auth::id() : 0;
        $userEmail = Auth::check() ? Auth::user()->email : '';

        $orderInfo = [
            'order_no' => $orderNumber,
            'status' => 'Payment pending',
            'user_id' => $userId,
            'email' => $userEmail,
        ];

        $order = Order::create($orderInfo);

        $userCart = Session::get('user_cart');
        foreach ($userCart as $cart_item) {
            $productId = $cart_item['product_id'];
            $quantity = $cart_item['quantity'];
            $item = Product::where('id', $productId)->first();

            if (!$item) {
                continue;
            }

            // Don't proceed with the payment if there isn't enough stock for
            // any item on the order.
            if ($item->quantity - $quantity < 0) {
                return redirect('/basket')->with('warning', $this->unableToCompleteOrderMessage);
            }

            array_push($stripeLineItems, [
                'name' => $item->name,
                'description' => $item->description,
                'images' => [getenv('APP_URL') . '/' . $item->image_path],
                'amount' => (int) ($item->price),
                'currency' => getenv('STRIPE_CURRENCY'),
                'quantity' => $quantity,
            ]);

            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $item->price,
                'total' => $item->price * $quantity,
            ]);

            $item->quantity -= $quantity;
            $item->save();
        }

        if (empty($stripeLineItems)) {
            return redirect('/basket')->with('warning', $this->emptyCartMessage);
        }

        Stripe::setApiKey($this->secretKey);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $stripeLineItems,
//            'success_url' => getenv('APP_URL') . $this->successPath,
//            'cancel_url' => getenv('APP_URL') . $this->failurePath,
            'success_url' => 'https://aab56b22.ngrok.io' . $this->successPath,
            'cancel_url' => 'https://aab56b22.ngrok.io' . $this->failurePath,
            'client_reference_id' => $orderNumber,
        ]);

        $stripeSession = $session->__get('id');
        $stripeKey = $this->publishableKey;

        return view('purchase.checkout')->with([
            'stripeSession' => $session->__get('id'),
            'stripeKey' => $this->publishableKey,
        ]);
    }

    public function checkoutResponse() {
        // Retrieve the request's body and parse it as JSON.
        $input = @file_get_contents('php://input');
        $event_json = json_decode($input);

        // Get order and customer info from the JSON.
        if (!empty($event_json->data) && !empty($event_json->data->object)) {
            $object = $event_json->data->object;
            $orderNumber = $object->client_reference_id;

            Stripe::setApiKey($this->secretKey);

            // Get the customer email from Stripe.
            $customer = Customer::retrieve($object->customer);
            $customerEmail = $customer->email;

            if ($event_json->type === $this->stripeCheckoutCompleteLabel) {
                // Update stocks.
                $userCart = Session::get('user_cart');
                foreach ($userCart as $cart_item) {
                    $productId = $cart_item['product_id'];
                    $quantity = $cart_item['quantity'];
                    $item = Product::where('id', $productId)->first();

                    if (!$item) {
                        continue;
                    }

                    $item->stock -= $quantity;
                }

                $userId = Auth::check() ? Auth::id() : 0;

                // Associate the user with the order(s) in the orders table, and set
                // the order status to Completed.
                $orders = Order::where('order_no', $orderNumber)->get();
                $orderTotal = 0;
                foreach ($orders as $order) {
                    $order->user_id = $userId;
                    $order->email = $customerEmail;
                    $order->status = 'Completed';
                    $order->save();

                    // Recalculate the total cost of the order.
                    $orderTotal += ($order->unit_price * $order->quantity);
                }

                // Send an email to the customer.
                $data = [
                    'to' => $customerEmail,
                    'subject' => 'Thank you for your purchase',
                    'view' => 'purchase',
                    'name' => '',
                    'body' => [
                        'order_no' => $orderNumber,
                        'total' => $orderTotal,
                    ],
                ];

                //        (new Mail())->send($data);
            }
        }

        // Return a response to acknowledge receipt of the event.
        http_response_code(200);
    }

    public function paymentSuccess() {
        $orderNumber = Session::get('order_number');
        $order = Order::where('order_no', $orderNumber)->first();
        Session::remove('order_number');
        Cart::clear();

        return view('purchase.success')->with('userEmail', $order->email);
    }

    public function paymentFailure() {
        // @todo Change status to Failed for failed order items.
        // @todo Change stock quantity for product(s) in failed order item(s).
    }
}
