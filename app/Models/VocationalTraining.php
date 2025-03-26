<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalTraining extends Model
{
    protected $fillable = [
        'content',
        'is_active',
    ];
}
