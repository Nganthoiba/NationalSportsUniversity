<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Sport extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sports';

    protected $fillable = ['sport_name', 'sport_name_in_hindi','created_by','updated_by','enabled'];
}
