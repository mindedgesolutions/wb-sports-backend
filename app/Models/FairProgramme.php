<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FairProgramme extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'occurance',
        'description',
        'uuid',
        'added_by',
        'updated_by',
        'organisation'
    ];
}
