<?php

namespace App\Http\Controllers;

use App\Http\Classes\Cart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private $emptyCartMessage = 'Your basket is currently empty';
    private $itemAddedMessage = 'The item has been added to your basket';
    private $itemRemovedMessage = 'The item has been removed from your basket';
    private $itemUpdatedMessage = 'Your basket has been updated.';

    public function show() {
        try {
            $items = [];
            $cartTotal = 0;

            if (!Session::has('user_cart') || count(Session::get('user_cart')) < 1) {
                return view('purchase.cart')->with('info', $this->emptyCartMessage);
            }

            $index = 0;
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
                    'stock' => $item->quantity,
                    'index' => $index,
                ]);
                $index++;
            }

            $cartTotal = number_format($cartTotal, 2);

            return view('purchase.cart')->with([
                'items' => $items,
                'cartTotal' => $cartTotal,
            ]);
        }
        catch (\Exception $exception){
            // Log to DB or email admin.
        }
    }

    public function addItem(Request $request) {
        Cart::add($request->product_id);
        return redirect('/products')->with('success', $this->itemAddedMessage);
    }

    public function removeItem(Request $request) {
        Cart::remove($request->product_id);
        return redirect('/basket')->with('success', $this->itemRemovedMessage);
    }

    public function updateItem(Request $request) {
        $update = Cart::update($request->product_id, $request->operation);
        return redirect('/basket')->with('success', $this->itemUpdatedMessage);
    }
}
