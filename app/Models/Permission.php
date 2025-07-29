<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Permission extends Model
{
    protected $connection = 'mongodb'; 
    protected $collection = 'permissions';
    protected $fillable = [
        'name', // a unique constant string for the permission
        'label', // Human readable name
        'description', // about the permission
        'group',
        'enabled', //wheather permission is enabled or disabled (true or false)
        'created_by', // The user who created the permission
        'updated_by', // the user who updated it
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
            $model->enabled = true;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }

}
