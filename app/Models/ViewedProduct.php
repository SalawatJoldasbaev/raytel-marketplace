<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewedProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'viewed_at',
        'product_id',
        'user_id',
        'device_id'
    ];
}
