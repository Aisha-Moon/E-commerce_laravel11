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
    public function index()
    {
        
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
