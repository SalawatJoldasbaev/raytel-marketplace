<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperStore
 */
class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'phone',
        'description',
        'telegram',
        'instagram',
        'active',
    ];

    public $casts = [
        'active'=> 'boolean'
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Product::class);
    }
}
