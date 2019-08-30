<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Classes\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    private $emptyCartMessage = 'Your basket is currently empty';
    private $itemAddedMessage = 'Item added to your basket';
    private $itemRemovedMessage = 'Item removed from your basket';

    public function show() {

    }

    public function addItem(Request $request) {
        Cart::add($request);
        return redirect('/products')->with('success', $this->itemAddedMessage);
    }

    public function removeItem() {

    }

    public function update() {

    }
}
