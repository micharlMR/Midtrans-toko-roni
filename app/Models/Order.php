<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'address',
        'phone_number',
        'total_amount',
        'settled_at',
    ];

    public function detail()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
