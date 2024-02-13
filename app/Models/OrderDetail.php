<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_detail';

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'total_amount',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class);
    }
}
