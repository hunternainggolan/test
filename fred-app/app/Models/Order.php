<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['id', 'name', 'customer_id', 'price', 'address', 'payment_status', 'driver_id', 'created_date', 'delivery_date', 'delivery_status'];
    const UPDATED_AT = null;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
