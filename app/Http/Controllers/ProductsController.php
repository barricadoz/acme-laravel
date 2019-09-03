<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $products = Product::orderBy('name', 'asc')->get();
        $productsByCategory = [];

        foreach ($products as $product) {
            $product->cartQuantity = 0;
            $category = $product->category->name;
            if (!array_key_exists($category, $productsByCategory)) {
                $productsByCategory[$category] = [];
            }

            if (Session::has('user_cart')) {
                $cart = Session::get('user_cart');

                foreach ($cart as $cart_item) {
                    if ((int) $cart_item['product_id'] === $product->id) {
                        $product->cartQuantity = $cart_item['quantity'];
                    }
                }
            }

            $productsByCategory[$category][] = $product;
        }

        return view('products.index')->with('productsByCategory', $productsByCategory);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $categoryOptions = [0 => 'Please select'];
        $categories = Category::orderBy('name', 'asc')->get();

        foreach ($categories as $category) {
            $categoryOptions[$category->id] = $category->name;
        }

        return view('admin.products.create')->with('categoryOptions', $categoryOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'required|mimes:jpeg, gif, png',
            'category' => 'required|not_in:0',
        ]);

        $file = $request->file('image');
        $fileExt = $file->getClientOriginalExtension();
        $fileDestination = '/public/images/products';
        $fileName = slug($request->input('name')) . '.' . $fileExt;
        $file->move('..' . $fileDestination, $fileName);

        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity');
        $product->image_path = $fileDestination . '/' . $fileName;
        $product->category_id = $request->input('category');

        $product->save();

        return redirect('/admin/product')->with('success', 'Product created');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $product = Product::find($id);
        $category = Category::find($product->category_id);

        return view('products.show')->with([
            'product' => $product,
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $product = Product::find($id);

        $categoryOptions = [0 => 'Please select'];
        $categories = Category::orderBy('name', 'asc')->get();

        $selectedCategory = $product->category_id;

        foreach ($categories as $category) {
            $categoryOptions[$category->id] = $category->name;
        }

        return view('admin.products.edit')->with([
            'product' => $product,
            'categoryOptions' => $categoryOptions,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'image' => 'mimes:jpeg, gif, png',
            'category' => 'required|not_in:0',
        ]);

        $product = Product::find($id);

        if (!empty($request->file('image'))) {
            $file = $request->file('image');
            $fileExt = $file->getClientOriginalExtension();
            $fileDestination = '/public/images/products';
            $fileName = slug($request->input('name')) . '.' . $fileExt;
            $file->move('..' . $fileDestination, $fileName);

            $product->image_path = $fileDestination . '/' . $fileName;
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->quantity = $request->input('quantity');
        $product->category_id = $request->input('category');

        $product->save();

        return redirect('/admin/product')->with('success', 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $product = Product::find($id);
        $product->delete();

        return redirect('/admin/product/')->with('success', 'Item deleted');
    }
}
