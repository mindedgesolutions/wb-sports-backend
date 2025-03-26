<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalTrainingCentre extends Model
{
    protected $fillable = [
    'district',
    'name_of_centre',
    'address',
    'phone',
    'is_active',
];
}
