<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRoleMapping;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserRoleMappingController extends Controller
{
    public function assignRoles(){
        $activeRoles = Role::where('enabled', true)->whereNotIn('role_name',[
            'Super Admin',
            'University Admin'
        ])->get();

        $users = User::getUniversityUsers(Auth::user()->university_id);

        return view('userrolemappings.assignRoles',[
            'users' => $users,
            'roles' => $activeRoles
        ]);
    }

    /** method to finalize the user-role mappings **/
    public function finalizeAssignment(Request $request){
        
        $request->validate([
            'selected_user_id' => 'required',
            'selected_roles' => 'nullable|array|min:1'
        ]);

        //Getting the mongodb client
        $client = DB::connection('mongodb')->getMongoClient();
        $session = $client->startSession();

        try{
            // Start transaction
            $session->startTransaction(); 
            UserRoleMapping::where('user_id', $request->selected_user_id)->delete();
            
            if(!is_null($request->selected_roles)){
                foreach($request->selected_roles as $role_id){
                    UserRoleMapping::create([
                        'user_id' => $request->selected_user_id,
                        'role_id' => $role_id,
                        'created_by' => Auth::user()->_id
                    ]);
                }
            }
            $message = is_null($request->selected_roles)?"All roles have been removed from the user.":
            "Selected roles have been assigned to the user successfully.";
            
            //Now commit the transaction
            $session->commitTransaction();
            if($request->wantsJson()){
                return response()->json([
                    'message' => $message
                ], 200);
            }

            return redirect()->back()->with([
                'success' => true,
                'message' => $message,
            ]);
            
        }
        catch(Exception $e){
            //Now commit the transaction
            $session->abortTransaction();
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'An error has occured while assigning roles to user.',
                    'errors' => $e,
                ], 500);
            }
            return redirect()->back()->with([
                'error' => true,
                'message' => 'An error has occured while assigning roles to user.',
                'errors' => $e,
            ]);          
        }

    }

    public function getAssignedRoles($user_id){
        $roles = UserRoleMapping::where('user_id', $user_id)->pluck('role_id');
        return response()->json([
            'roles' => $roles
        ]);
    }
}
