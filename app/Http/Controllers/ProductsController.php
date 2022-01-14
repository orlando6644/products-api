<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    private $validation_rules;

    public function __construct()
    {
        $this->validation_rules = [
            'name'          => 'required|max:150|min:2',
            'description'   => 'required|min:2',
            'price'         => 'required|numeric',
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['products' => Product::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), $this->validation_rules);


        if ($validation->fails()) {
            return response()->json(['error' => true,'description' => $validation->errors()],422);
        }

        $new_product = new Product();
        $new_product->name = $request->input('name');
        $new_product->description = $request->input('description');
        $new_product->price = $request->input('price');
        $new_product->photo = $request->input('photo');
        $new_product->save();

        return response()->json(['success' => true,'product' => $new_product]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json(['success' => true,'product' => $product]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true,'description' => 'Register not found.'],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $validation = Validator::make($request->all(), $this->validation_rules);

        if ($validation->fails()) {
            return response()->json(['error' => true,'description' => $validation->errors()],422);
        }

        $product = Product::find($id);

        if(!$product){
            return response()->json(['error' => true,'description' => 'Register not found.'],404);
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->photo = $request->input('photo');
        $product->save();

        return response()->json(['success' => true,'product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if($product){
            $product->delete();
        }

        return response()->json(['success' => true]);
    }
}


