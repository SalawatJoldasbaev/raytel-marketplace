<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'store_id',
        'image',
        'description',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
