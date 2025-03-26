<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VocationalTrainingCentre extends Model
{
    protected $fillable = [
    'district',
    'nameOfcentre',
    'Address',
    'Phone',
    'is_active',
];
}
