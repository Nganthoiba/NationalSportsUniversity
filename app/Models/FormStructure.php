<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class FormStructure extends Model
{
    protected $connection = 'mongodb'; // Ensure it uses MongoDB connection
    protected $collection = 'form_structures';
}
