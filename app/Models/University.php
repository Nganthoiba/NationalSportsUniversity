<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class University extends Model
{
    protected $table = 'universities';
    protected $fillable = ['name', 'email'];
}
