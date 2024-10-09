<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
   
    function InvoicePage()
    {
        return view('pages.dashboard.invoice-page');
    }
    function SalePage()
    {
        return view('pages.dashboard.sale-page');
    }
    function invoiceCreate(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');

            $customer_id = $request->input('customer_id');

            $invoice = Invoice::create([
                'user_id' => $user_id,
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'customer_id' => $customer_id
            ]);
            $invoiceId = $invoice->id;
            $products = $request->input('products');
       
            foreach ($products as $eachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoiceId,
                    'user_id' => $user_id,
                    'product_id' => $eachProduct['product_id'],
                    'quantity' => $eachProduct['quantity'],
                    'sale_price' => $eachProduct['sale_price'],


                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Invoice created successfully.'], 201);


        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Invoice creation failed.'], 500);
        }

    }
    public function invoiceSelect(Request $request)
    {
        $user_id = $request->header('id');
        $invoices = Invoice::where('user_id', $user_id)->with('customer')->get();
        return response()->json($invoices, 200);
    }
    function InvoiceDetails(Request $request)
    {
        $user_id = $request->header('id');
        $customerDetails = Customer::where('user_id', $user_id)->where('id', $request->input('customer_id'))->first();
        $invoiceTotal = Invoice::where('user_id', '=', $user_id)->where('id', $request->input('invoice_id'))->first();
        $invoiceProduct = InvoiceProduct::where('invoice_id', $request->input('invoice_id'))
            ->where('user_id', $user_id)->with('product')
            ->get();
        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct,
        );
    }

    function invoiceDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            InvoiceProduct::where('invoice_id', $request->input('invoice_id'))
                ->where('user_id', $user_id)
                ->delete();
            Invoice::where('id', $request->input('invoice_id'))->delete();
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            return 0;
        }
    }

}
