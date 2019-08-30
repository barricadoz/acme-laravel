<?php


namespace App\Http\Classes;


use Illuminate\Support\Facades\Session;

class Cart {
    public static function add($request) {
        $ItemIsInCart = FALSE;

        try {
            $index = 0;

            if (!Session::has('user_cart') || count(Session::get('user_cart')) < 1) {
                Session::put('user_cart', [
                    0 => [
                        'product_id' => $request->product_id,
                        'quantity' => 1,
                    ],
                ]);
            }
            else {
                $userCart = Session::get('user_cart');
                foreach ($userCart as $cartItems) {
                    $index++;
                    foreach ($cartItems as $key => $value) {
                        if ($key === 'product_id' && $value === $request->product_id) {
                            array_splice($userCart, $index - 1, 1, array([
                                'product_id' => $request->product_id,
                                'quantity' => $cartItems['quantity'] + 1,
                            ]));
                            $ItemIsInCart = TRUE;
                        }
                    }
                }

                if (!$ItemIsInCart) {
                    array_push($userCart, [
                        'product_id' => $request->product_id,
                        'quantity' => 1,
                    ]);
                }

                Session::put('user_cart', $userCart);
            }
        }
        catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
}
