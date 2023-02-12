<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method with()
 * @method create()
 */

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'store_id',
        'image',
        'description',
        'watermark_image',
    ];
    protected $casts = [
        'path'=> 'json'
    ];
    public function store(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
