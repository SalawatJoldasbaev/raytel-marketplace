<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperSettings
 */
class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'title',
        'price',
        'card_number',
        'card_holder',
        'phone',
        'end_text',
        'block_text',
        'unblock_text',
        'watermark_text',
    ];
}
