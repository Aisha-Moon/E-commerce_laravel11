<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function page(){
        return view('pages.dashboard.product-page');
    }
    public function index(Request $request)
    {
        $user_id=$request->header('id');
        return Product::where('user_id', $user_id)->get();
    } 

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $user_id = $request->header('id');
    
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|string',
            'unit' => 'required|string',
            'img_url' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);
        $img=$request->file('img_url');
    
        $filename=$img->getClientOriginalName();
        $img_url="uploads/$filename";
        $img->move(public_path('uploads'),$filename);
       
    
        $product = Product::create([
            'user_id' => $user_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'unit' => $request->unit,
            'img_url' => $img_url
        ]);
    
        return response()->json(['message' => 'Product created successfully.', 'product' => $product], 201);
    }
    


    /**
     * Store a newly created resource in storage.
     */
 

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $user_id = $request->header('id');
        

        $product = Product::where('user_id', $user_id)->where('id', $id)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
    
        $user_id = $request->header('id');
        $product = Product::where('user_id', $user_id)->where('id', $id)->first();
    
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
    
        if ($request->hasFile('img_url')) {
            $img = $request->file('img_url');
            $filename = $img->getClientOriginalName();
            $img_url = "uploads/$filename";
            $img->move(public_path('uploads'), $filename);
    
            if ($product->img_url) {
                $imagePath = public_path($product->img_url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
    
            $product->img_url = $img_url;
        }
    
        $product->category_id = $request->input('category_id');
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->unit = $request->input('unit');
        $product->save();
    
        return response()->json(['message' => 'Product updated successfully.'], 200);
    }
 

    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
       
        $user_id = $request->header('id');
        $product=Product::where('user_id', $user_id)->where('id', $id)->first();
     
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }
    
        // Check if the product has an image and delete the file from the storage
        if ($product->img_url) {
            $imagePath = public_path($product->img_url); // Get the full path of the image
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the file
            }
        }
    
        // Delete the product from the database
        $product->delete();
    
        return response()->json(['message' => 'Product deleted successfully.'], 200);
    }
    
}
