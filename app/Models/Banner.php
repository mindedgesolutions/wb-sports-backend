<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'page_url',
        'image_path',
        'is_active',
        'added_by',
        'updated_by'
    ];
}
