<?php

namespace App\Http\Controllers;

use App\Product;
use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    private $emptyCartMessage = 'Your basket is currently empty';
    private $unableToCompleteOrderMessage = 'Sorry - there was a problem processing your order and it could not be completed at this time';
    private $stripeCheckoutCompleteLabel = 'checkout.session.completed';
    private $successPath = '/checkout/complete';
    private $failurePath = '/checkout/failed';

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

        $orderInfo = [
          'order_no' => $orderNumber,
          'status' => 'Payment pending',
        ];

        if (Auth::check()) {
          $user = Auth::id();
          $orderInfo['user_id'] = $user;
        }

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
          if ($item->quantity - $quantity < 0) {;
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

        $session = \Stripe\Checkout\Session::create([
          'payment_method_types' => ['card'],
          'line_items' => $stripeLineItems,
          'success_url' => getenv('APP_URL') . $this->successPath,
          'cancel_url' => getenv('APP_URL') . $this->failurePath,
          'client_reference_id' => $orderNumber,
        ]);

        $viewVars = [
          'stripeSession' => $session->__get('id'),
          'stripeKey' => getenv('STRIPE_PUBLISHER_KEY'),
        ];

        return view('purchase.checkout', $viewVars);
    }

    public function checkoutResponse() {

    }

    public function paymentSuccess() {

    }

    public function paymentFailure() {

    }
}
