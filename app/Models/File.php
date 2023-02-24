<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperFile
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'url',
    ];
}
