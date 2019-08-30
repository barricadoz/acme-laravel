<?php


namespace App\Http\Classes;


use Illuminate\Support\Facades\Session;

class Cart {
    private $itemAddedMessage = 'Item added to your basket';

    public static function add($productId) {
        $ItemIsInCart = FALSE;

        try {
            $index = 0;

            if (!Session::has('user_cart') || count(Session::get('user_cart')) < 1) {
                Session::put('user_cart', [
                    0 => [
                        'product_id' => $productId,
                        'quantity' => 1,
                    ],
                ]);
            }
            else {
                $userCart = Session::get('user_cart');
                foreach ($userCart as $cartItems) {
                    $index++;
                    foreach ($cartItems as $key => $value) {
                        if ($key === 'product_id' && $value === $productId) {
                            array_splice($userCart, $index - 1, 1, array([
                                'product_id' => $productId,
                                'quantity' => $cartItems['quantity'] + 1,
                            ]));
                            $ItemIsInCart = TRUE;
                        }
                    }
                }

                if (!$ItemIsInCart) {
                    array_push($userCart, [
                        'product_id' => $productId,
                        'quantity' => 1,
                    ]);
                }

                Session::put('user_cart', $userCart);
            }

            $object = new static();
            return $object->itemAddedMessage;
        }
        catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public static function remove($index) {
        if (count(Session::get('user_cart')) <= 1) {
            self::clear();
        }
        else {
            $userCart = Session::get('user_cart');
            unset($userCart[$index]);
            sort($userCart);

            Session::put('user_cart', $userCart);
        }
    }

    public static function clear() {
        Session::forget('user_cart');
    }
}
