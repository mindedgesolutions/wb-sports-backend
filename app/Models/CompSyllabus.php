<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompSyllabus extends Model
{
    protected $fillable = [
        'name',
        'file_path',
        'organisation',
        'added_by',
        'is_active'
    ];
}
