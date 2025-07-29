<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Http\Requests\AssignMenuRequest;

class MenuController extends Controller
{
    //This method is for assigning accessible menus according to roles
    public function assignMenuRoles(AssignMenuRequest $request){
        if($request->getMethod() == "POST"){

            $data = $request->validated();

            try{
                $role = Role::find($data['role_id']);
                $role->allowed_menus = $data['allowed_menus'];
                $role->save();
            }catch(Exception $e){
                return redirect()->back()->with([
                    'success' => true,
                    'message' => 'An error occurs while assigning menus to the role.'
                ]);
            }           

            return redirect()->back()->with([
                'success' => true,
                'message' => 'You have successfully assigned menus to the role.'
            ]);            

        }

        $currentRole = session('currentRole');
        

        if($currentRole){
            //dd($currentRole);
            switch($currentRole->role_name){
                case 'Super Admin':
                    $roles = Role::whereNotIn('role_name', ['Super Admin'])->get();
                    $menus = array_filter(config('menus'), function ($menu){
                        if(!in_array('Super Admin', $menu['allowed_roles']??[])){
                            return true;
                        }
                        return false;
                    });
                    /* 
                    $menus = array_filter(config('menus'), function ($menu){
                        if(empty(array_intersect(['Super Admin','University Admin'], $menu['allowed_roles']??[]))){
                            return true;
                        }
                        return false;
                    }); */

                    break;
                case 'University Admin':
                    $roles = Role::whereNotIn('role_name', ['Super Admin', 'University Admin'])->get();
                    $menus = array_filter(config('menus'), function ($menu){
                        if(empty(array_intersect(['Super Admin','University Admin'], $menu['allowed_roles']??[]))){
                            return true;
                        }
                        return false;
                    }); 
                    break;
                default:
                    $roles = Role::all();
                    $menus = config('menus');

            }
            return view('menu.assignMenuRoles',[
                'roles' => $roles,
                'menus' => $menus,
            ]);
        }
        
        return view('layout.errorMessage',[
            'title' => 'Session Expired',
            'message' => 'Your session is expired, please login again.'
        ]); 
        
    }
}
