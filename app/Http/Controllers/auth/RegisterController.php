<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegistrationRequest $request) {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if(empty($user)){
            return redirect()->back()->with(['error' => true, 'message' => 'You email is still not registered yet by your concerned administrator.']);
        }

        $user->password = Hash::make($data['password']);
        $user->enabled = true; // by default every new user is not approved
        $user->save();

        Auth::login($user);

        //return redirect()->route('home')->with('success', 'Registration successful!');
        return redirect()->route('home')->with(['success' => true, 'message' => 'Registration successful!']);
    }
    public function showRegistrationForm(Request $request) {
        return view('authenticate.register',[
            'email' => $request->email??''
        ]);
    }
}
