<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';
    protected $fillable = ['id', 'order_id', 'payment_method', 'value', 'transaction_date'];
    const CREATED_AT = 'transaction_date';
    const UPDATED_AT = null;
}
