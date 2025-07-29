<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use MongoDB\Laravel\Eloquent\Model;

class Course extends Model
{
    protected $connection = 'mongodb'; 
    protected $collection = 'courses'; 
    protected $fillable = ['course_name', 'short_form'];
}
