<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Role extends Model
{
    /*
    'role_name' => 'required|string|max:255',
            'role_description' => 'nullable|string|max:255',
    */
    protected $connection = 'mongodb'; 
    protected $collection = 'departments';
    protected $fillable = ['role_name', 'role_description', 'created_by', 'updated_by','enabled', 'permission_names', 'changeable'];

    public function permissions()
    {
        if(!isset($this->permission_names)){
            return [];
        }
        //return Permission::whereIn('name', $this->permission_names)->get();
        return $this->permission_names;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
            $model->enabled = true;
            //$model->changeable = true;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }

    public function hasPermission($permission){
        if (is_string($permission) && in_array($permission, $this->permission_names)) {
            return true;
        }

        if(is_array($permission)){
            $intersect = array_intersect($permission, $this->permission_names);
            if(empty($intersect) || sizeof($permission) != sizeof($intersect)){
                return false;
            }
            return true;
        }
        return false;
    }
}
