<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class LoginController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $currentRole = $user->getRoles()->first()??null;
            $request->session()->put('currentRole', $currentRole);
            return redirect()->intended('dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showLoginForm() {
        return view('authenticate.login');
    }

    //method to switch user role
    public function switchRole(Request $request, $roleId){
        $currentRole = Role::find($roleId);
        if(!empty($currentRole)){
            $request->session()->put('currentRole', $currentRole);
            return redirect()->back()->with([
                'success' => true,
                'message' => 'You have switch role to '.$currentRole->role_name
            ]);
        }
        return redirect()->back()->with([
            'error' => true,
            'message' => 'Unable to switch role'
        ]);        
    }
}
