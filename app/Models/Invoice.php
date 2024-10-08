<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',  
        'total',
        'discount',
        'vat',
        'payable',
        'customer_id'
    ];

    function customer(){
        return $this->belongsTo(Customer::class);
    }

    function invoices(){
        return $this->hasMany(InvoiceProduct::class);
    }

}
