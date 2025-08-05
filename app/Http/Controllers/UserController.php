<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRoleMapping;
use App\Models\University;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function createUniversityUser(CreateUserRequest $request){

        if(!Auth::user()->hasPermission('create_user')){
            return view('layout.errorMessage',[
                'title' => 'Not permitted',
                'message' => 'Sorry, you do not have permission to create user.'
            ]);
        }
        
        $university = University::find(Auth::user()->university_id);
        return view('users.createUniversityStaff',[
            'university' => $university,
            'roles' => Role::whereNotIn('role_name',['Super Admin', 'University Admin'])->where('enabled', true)->get()
        ]);
    }

    public function createUser(CreateUserRequest $request){

        if(!Auth::user()->hasPermission('create_user')){
            return view('layout.errorMessage',[
                'title' => 'Not permitted',
                'message' => 'Sorry, you do not have permission to create user.'
            ]);
        }
        
        $data = $request->validated();

        //Getting the mongodb client
        $client = DB::connection('mongodb')->getMongoClient();
        $session = $client->startSession();
        try{
            $session->startTransaction();
            $user = new User();
            $user->full_name = $data['full_name'];
            $user->email = $data['email'];
            $user->contact_no = $data['contact_no'];
            $user->university_id = $data['university_id'];
            $user->place_of_posting = $data['place_of_posting'];
            $user->designation = $data['designation'];
            $user->created_by = Auth::user()->id;
            $user->enabled = true;
            
            $user->save();

            UserRoleMapping::create([
                'user_id' => $user->id,
                'role_id' => $data['role_id'],
                'created_by' => Auth::user()->id
            ]);

            $session->commitTransaction();

            //Mail to the registered email id for information to the user so that the user himself can complete registration by setting up his/her password
            Mail::send('emails.passwordSetup', [
                'user' => $user,
                'passwordSetupUrl' => route('register').'?email='.$user->email,
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Complete Your Registration');
            });

        }
        catch(Exception $e){
            $session->abortTransaction();
            if($request->wantsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error' => true
                ], 403);
            }

            return redirect()->back()->with([
                'message' => 'Sorry an error has occured. '.$e->getMessage(),
                'success' => false,
                'error' => true
            ]);
        }
        finally{            
            $session->endSession();
            $session = null;
            $client = null;
        }
        

        if($request->wantsJson()){
            return response()->json([
                'message' => 'User account has been created'
            ], 201);
        }

        return redirect()->back()->with([
            'message' => 'User account has been created',
            'success' => true,
            'error' => false
        ]);
    }

    public function createUniversityAdminUser(CreateUserRequest $request){
        if(!Auth::user()->hasPermission('create_user')){
            return view('layout.errorMessage',[
                'title' => 'Not permitted',
                'message' => 'Sorry, you do not have permission to create user.'
            ]);
        }
        return view('users.createUniversityAdmin',[
            'universities' => University::all(),
            'role' => Role::where('role_name', 'University Admin')->first()
        ]);
    }

    //Reset all passwords
    public function resetAllPasswords() {
        $users = User::all();
        foreach ($users as $user) {
            $user->password = Hash::make('Test@123');
            $user->save();
        }
        return response()->json(['message' => 'All passwords have been reset to Test@123']);
    }

    //Get University Admin users
    public function getUniversityAdmins(Request $request){
        $users = User::getUniversityAdminUsers();
        return view('users.index',[
            'users' => $users,
            'title' => 'University Admin Users',
            'userType' => 'admins',
            'roles' => Role::whereNotIn('role_name', [
                'Super Admin',
            ])->where('enabled', true)->orderBy('role_name')->get()
        ]);
    }

    //Get University other non-admin users
    public function getUniversityUsers(Request $request){
        $users = User::getUniversityUsers(Auth::user()->university_id);
        $university = University::find(Auth::user()->university_id);
        $title = empty($university)?'Users for all universities':'Users for '.$university->name;
        return view('users.index',[
            'users' => $users,
            'title' => $title,
            'userType' => 'staffs',
            'roles' => Role::whereNotIn('role_name', [
                'Super Admin',
                'University Admin'
            ])->where('enabled', true)->orderBy('role_name')->get()
        ]);
    }

    //Method to either enable or disable a user
    public function enableOrDisable(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'enabled' => ['required', Rule::in(['true', 'false'])]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::find($request->user_id);
        if($user){
            $enabled = $request->enabled =="true"?true:false;
            $user->update([
                'enabled' => $enabled,
                'updated_by' => Auth::user()->id
            ]);
        }
        return redirect()->back()->with([
            'success' => true,
            'message' => $request->enabled =="true"?"User has been enabled successfuly.":
            "User has been disabled successfully."
        ]);
    }
}
