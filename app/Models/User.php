<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use App\Models\UserRoleMapping;
use App\Models\University;
use Exception;

class User extends Authenticatable implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Notifiable, Authorizable, CanResetPassword, MustVerifyEmail, SoftDeletes;
    use HasFactory;

    protected $connection = 'mongodb'; // Specify MongoDB connection
    protected $collection = 'users'; // Optional, default is the plural form of the model name

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'email_verified_at',
        'university_id',
        'role',
        'contact_no',
        'place_of_posting',
        'designation',
        'profile_img',
        'enabled', //enable or disable (booloan value)
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /***
     * Method to get the mapped role names
     */
    public function getRoles(array $fields=[]){
        //get mapped role_ids
        $role_ids = UserRoleMapping::where('user_id', $this->_id)->pluck("role_id");
        // Ensure the user has a role assigned
        if ($role_ids->isEmpty()) {
            return collect(); // or return false, but collect() is more Laravel-ish
        }
        return Role::whereIn('_id', $role_ids)->where('enabled', true)->orderBy('role_name')->get($fields);
    }

    
    public function getAssignedRoles(){
        //get mapped role_ids
        $role_ids = UserRoleMapping::where('user_id', $this->_id)->pluck("role_id");
        // Ensure the user has a role assigned
        if (empty($role_ids)) {
            return false;
        }

        return Role::whereIn('_id', $role_ids)->where('enabled', true)->pluck("role_name")->toArray();
    }


    /**
     * Check if the user is assigned all the specified roles.
     *
     * This method checks if the user has all the roles specified in the given array.
     * 
     * Usage:
     * ```php
     * $flag = $user->isRole(['admin', 'supervisor']);
     * ```
     * If the user has both 'admin' and 'supervisor' roles, the method will return `true`.
     * Otherwise, it will return `false`.
     *
     * @param array $roleNames An array of role names to check.
     * @return bool Returns `true` if the user has all the specified roles, otherwise `false`.
     * @throws InvalidArgumentException If the $roleNames array is empty.
     */    

    public function isRole(array $roleNames): bool
    {    
        $roles = array_map(function ($item){
            return strtolower($item);
        }, $roleNames);

        $mappedRoleNames = array_map(function($roleName){
            return strtolower($roleName);
        },$this->getAssignedRoles());
        // Check if all specified roles exist in the user's roles
        return empty(array_intersect($roles, $mappedRoleNames));
    }

    public function hasRole($role_name):bool{
        //$mappedRoleNames = $this->getAssignedRoles();
        $mappedRoleNames = array_map(function($roleName){
            return strtolower($roleName);
        },$this->getAssignedRoles());
        return in_array(strtolower($role_name), $mappedRoleNames, true);
    }

    public function hasPermission($permission)
    {        
        //Trying to get role_id from session
        $currentRole = session('currentRole');
        $role = Role::find($currentRole->id);
        return $role->hasPermission($permission);
    }

    //get university admin users
    public function scopeGetUniversityAdminUsers($query){
        //Get State Admin Role Id
        $university_admin_role_id = Role::whereLike('role_name', 'University Admin%')->first();
        //Get all the user Ids from the user_role_mappings collection whose role id is above retrieved.
        $user_ids = UserRoleMapping::where('role_id', $university_admin_role_id->id)->pluck('user_id');
        $users = $query->whereIn('_id', $user_ids)->get()->map(function($user){
            $user->university_name = University::find($user->university_id)->name;
            $user->creator = User::find($user->created_by);
            return $user;
        });
        return $users;
    }

    //get university admin users
    public function scopeGetUniversityUsers($query, $university_id = null){
        //Get State Admin Role Id
        $university_admin_role_ids = Role::whereIn('role_name', ['Super Admin'])
        ->get()->map(function($role){
            return $role->_id;
        });
        //Get all the user Ids from the user_role_mappings collection whose role id is above retrieved.
        $user_ids = UserRoleMapping::whereIn('role_id', $university_admin_role_ids)->pluck('user_id'); 
        $query->whereNotIn('_id', $user_ids);
        if(!is_null($university_id)){
            $query->where('university_id', $university_id);
        }        
        $users = $query->get()->map(function($user){
            //$creator = User::find($user->created_by);
            $user->university_name = University::find($user->university_id)->name;
            $user->creator = User::find($user->created_by);
            return $user;
        });
        return $users;
    }    

}