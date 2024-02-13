<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'subtotal',
    ];

    public function Orders()
    {
        return $this->belongsToMany(Orders::class)->using(OrderDetails::class)->withPivot('qty', 'subtotal');
    }
}
