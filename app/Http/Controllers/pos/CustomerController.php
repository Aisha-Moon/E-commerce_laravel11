<?php

namespace App\Http\Controllers\pos;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function page()
    {
        return view('pages.dashboard.customer-page');
    }

    public function index(Request $request)
    {
        $user_id=$request->header('id');
        return Customer::where('user_id', $user_id)->get();
    }        

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $user_id = $request->header('id');

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'mobile' => 'nullable|string|max:15',
        ]);

        // Create a new customer with the validated data
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'user_id' => $user_id, // Associate with authenticated user
        ]);

        return response()->json(['message' => 'Customer created successfully.', 'customer' => $customer], 201);
    }

    public function show(Request $request,$id){
        $user_id = $request->header('id');
        

        $customer = Customer::where('user_id', $user_id)->where('id', $id)->first();
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
        return response()->json(['customer' => $customer], 200);
    }

 
    public function update(Request $request, string $id)
    
    {
      
        $user_id = $request->header('id');
    
        $customer = Customer::where('user_id', $user_id)->where('id', $id)->first();
    
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
    
       
        $customer->update($request->only('name', 'email', 'mobile'));
    
        return response()->json(['message' => 'Customer updated successfully.', 'customer' => $customer], 200);
    }

  
    public function destroy(Request $request, string $id)
    {
        $user_id = $request->header('id');
        
        // Find the customer by ID and user_id to ensure it's owned by the current user
        $customer = Customer::where('user_id', $user_id)->where('id', $id)->first();
        
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }
        
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully.'], 200);
    }
}
