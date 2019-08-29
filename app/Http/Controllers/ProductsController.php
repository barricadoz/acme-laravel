<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('name', 'asc')->get();
        $productsByCategory = [];

        foreach ($products as $product) {
            $category = $product->category->name;
            if (!array_key_exists($category, $productsByCategory)) {
                $productsByCategory[$category] = [];
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
    public function create()
    {
        $categoryOptions = [0 => 'Please select'];
        $categories = Category::orderBy('name', 'asc')->get();

        foreach($categories as $category) {
            $categoryOptions[$category->id] = $category->name;
        }

        return view('admin.products.create')->with('categoryOptions', $categoryOptions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
