<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Products;

class indexController extends Controller
{
    public function index(){
    	$products = Products::orderBy('created_at', 'DESC')->get();
    	return view('product_page')->with([
    		'products'	=>	$products
    	]);
    }

    public function addProduct(Request $request){
    	$data = $request->all();
    	$product = new Products();
    	$product->name = $data['name'];
    	$product->quantity = $data['quantity'];
    	$product->price = $data['price'];
    	$product->save();

    	return response()->json(['product' => $product]);
    }
}
