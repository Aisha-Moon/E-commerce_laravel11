<?php

namespace App\Http\Controllers\pos;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function page(){
        return view('pages.dashboard.category-page');
    }
    public function index(Request $request)
    {
        $user_id=$request->header('id');
        return Category::where('user_id', $user_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
       
 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id=$request->header('id');
      
        Category::create([
            'name'=>$request->name,
            'user_id'=>$user_id
        ]);
        return response()->json(['message' => 'Category created successfully.'], 201);
    }


   
    public function update(Request $request, string $id)
    {
        $user_id = $request->header('id');
    
        
        $category = Category::where('user_id', $user_id)->where('id', $id)->first();
    
       
        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }
    
        
        $category->name = $request->name; 
        $category->save();
    
        return response()->json(['message' => 'Category updated successfully.'], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,string $id)
    {
        $user_id=$request->header('id');
        $category_id=$request->input('id');
        Category::where('user_id', $user_id)->where('id', $category_id)->delete();
        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
