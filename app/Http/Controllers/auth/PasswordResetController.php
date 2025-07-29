<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;

use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request){
        return view('authenticate.forgotPassword');
    }

    // Method to send password reset link
    public function sendPasswordResetLink(Request $request){
        $request->validate([
            'email' => 'required|email'
        ]);
        $email = $request->post('email');
        $user = User::where('email', $email)->first();
        if(empty($user)){
            return redirect()->back()->with([
                'error' => 'Sorry, the email is not registered in our application system'
            ]);
        }

        $token = Str::random(64);

        PasswordReset::updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetUrl = url("/reset-password/{$token}?email=" . urlencode($request->email));
        // Sending plain email
        /*
        Mail::raw("Reset your password: $resetUrl", function ($message) use ($request) {
            $message->to($request->email)->subject('Password Reset Link');
        });*/

        Mail::send('emails.passwordResetLink', [
            'name' => $user->full_name ?? 'User',
            'resetLink' => $resetUrl
        ], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Your Password');
        });

        return back()->with('success', 'Password reset link has been sent to your email.');
    }

    //Method to display view for resetting a new password, new password and confirm password
    public function resetPassword(Request $request, string $token){
        
        return view('authenticate.resetPassword',[
            'token' => $token,
            'email' => $request->query('email')
        ]);
    }

    //Method to update the new password
    public function updatePassword(Request $request){
        $request->validate([
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password',
            'token' => 'required',
            'email' => 'required',
        ], [
            'new_password.required' => 'Please enter your new password.',
            'confirm_new_password.required' => 'Please confirm your new password.',
            'confirm_new_password.same' => 'The confirmation password does not match.',
        ]);

        $passwordReset = PasswordReset::where([
            'token' => $request->post('token'),
            'email' => $request->post('email'),
            ])->first();
        if(empty($passwordReset)){
            return redirect()->back()->with([
                'error' => 'Your password reset link does not exist. Please try after regenerating a new password reset link.'
            ]);
        }
        
        $expires = Carbon::parse($passwordReset->created_at)->addMinutes((int)env('PASSWORD_RESET_LINK_EXPIRE', 60));
        if (Carbon::now()->greaterThan($expires)) {
            //return back()->withErrors(['email' => 'Token expired.']);
            return redirect()->back()->with([
                'error' => 'Sorry, the password reset link is expired. Please try after regenerating a new password reset link.'
            ]);
        }

        $user = User::where('email', $request->post('email'))->first();
        if(empty($user)){
            return redirect()->back()->with([
                'error' => 'Sorry, the email is not registered in our application system.'
            ]);
        }

        $user->password = Hash::make($request->post('new_password'));
        $user->save();

        $passwordReset->delete();

        return redirect()->back()->with([
                'success' => 'You have successfully updated your password. You can try login with your new password'
            ]);

    }

    //Method to generate OTP
    public function generateAndSendOTP(Request $request){
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $user = User::where('email', $request->email)->first();
        if(empty($user)){
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, this email is not registered in our application system.',
                    'errors' => [
                        'email' => 'Sorry, this email is not registered in our application system.'
                    ]
                ], 422);
            }

            return back()->withInput()->withErrors([
                'email' => 'Sorry, this email is not registered in our application system.'
            ]);
        }
        

        $otp = [
            'email' => $user->email,
            'otp_id' => $this->generateOtpId(4),
            'otp_val' => '123456',//$this->generateOTP()
            'created_at' => now()
        ];

        session()->put('otp_data', $otp);

        
        Mail::send('emails.otpGenerated', [
            'user_name' => $user->full_name,
            'email' => $user->email,
            'otp' => $otp,
        ], function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Reset Your Password');
        });        
        

        if($request->wantsJson()){
            return response()->json([
                'success' => true,
                'message' => 'An OTP has been sent to your registered email. Your OTP Id is '.$otp['otp_id'],
                'otp_id' => $otp['otp_id']
            ]);
        }

        return back()->with([
            'success' => true,
            'message' => 'An OTP has been sent to your registered email. Your OTP Id is '.$otp['otp_id'],
            'otp_initiated' => true,
            'otp_id' => $otp['otp_id'],
        ])->withInput();
    }

    //method to verify otp and create password
    public function verifyOTPSetPassword(Request $request){
        
        $validator = Validator::make($request->all(), [
            'otp_id' => 'required',
            'otp' => 'required',
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password',
        ]);

        if ($validator->fails()) {
           if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
            ->with([
                'otp_initiated' => true,
            ])->withErrors($validator)
                ->withInput();
        }

        // Step 2: Fetch stored OTP data from session
        $otpData = session('otp_data');

        // Step 3: Check if session has OTP and matches the submitted otp_id
        if (!$otpData || $request->otp_id !== $otpData['otp_id']) {      
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'Invalid or expired OTP ID.'
                ], 403);
            }
            
            return back()->with([
                'otp_initiated' => true,
                'otp_id' => $otpData['otp_id'],
            ])->withErrors([
                'otp_id' => 'Invalid or expired OTP ID.'
            ])->withInput(); 
            //die("Invald OTP ID");
        }

        //Check if OTP expires OTP_EXPIRE
        $expires = Carbon::parse($otpData['created_at'])->addMinutes((int)env('OTP_EXPIRE', 6));
        if (Carbon::now()->greaterThan($expires)) {
           
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'OTP is expired.'
                ], 403);
            }            

            return back()->with([
                'otp_initiated' => true,
                'otp_id' => $otpData['otp_id'],
            ])->withErrors([
                'otp' => 'OTP is expired.'
            ])->withInput();

        }

        if($otpData['otp_val'] !== $request->otp){

            if($request->wantsJson()){
                return response()->json([
                    'message' => 'Invalid OTP.'
                ], 403);
            }

            return back()->with([
                'otp_initiated' => true,
                'otp_id' => $otpData['otp_id'],
            ])->withErrors([
                'otp' => 'Invalid OTP.'
            ])->withInput();
            //die("Invalid OTP, old OTP: {$otpData['otp_val']} - entered OTP: {$request->otp}");
        }

        $user = User::where('email', $otpData['email'])->first();
        $user->password = Hash::make($request->post('new_password'));
        $user->save();


        if($request->wantsJson()){
            return response()->json([
                'message' => 'You have successfully reset your password, you can log in.'
            ], 200);
        }

        return redirect()->route('login')->with([
            "success"=>true,
            "message"=>"You have successfully reset your password, you can log in."]);

    } 

    //method to generate OTP
    private function generateOTP($length = 6) {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }
        return $otp;
    }

    private function generateOtpId($length = 5) {
        return substr(str_shuffle(bin2hex(random_bytes(4))), 0, $length);
    }
}
