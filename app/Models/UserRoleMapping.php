<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class UserRoleMapping extends Model
{
     protected $fillable = ['user_id', 'role_id', 'created_by'];
}
