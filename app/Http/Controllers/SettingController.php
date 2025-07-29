<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * This method is for user setting: changing password.
     */
    public function userSetting(){
        $user = Auth::user();
        return view('setting.setting',[
            'user' => $user
        ]);
    }

    /**
     * Handle the password change request for the authenticated user.
     *
     * This method processes the password change request. It validates the input using the 
     * `ChangePasswordRequest` form request, updates the user's password if the validation passes, 
     * and provides appropriate feedback to the user.
     *
     * - If the request method is POST, it validates the input and updates the password.
     * - If the request method is GET, it renders the change password view.
     *
     * @param ChangePasswordRequest $request The validated request containing the current password, 
     *                                        new password, and confirmation password.
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *         - Redirects back with a success or error message after processing the POST request.
     *         - Returns the change password view for GET requests.
     *
     * @throws \Exception If an error occurs during the password update process.
     *
     * Example Usage:
     * - For GET requests: Displays the change password form.
     * - For POST requests: Validates and updates the user's password.
     *
     * Validation Rules:
     * - `current_password`: Must match the authenticated user's current password.
     * - `new_password`: Must be at least 8 characters long and different from the current password.
     * - `confirm_new_password`: Must match the `new_password`.
     *
     * Example Success Response:
     * - Redirects back with a success message: "Password changed successfully."
     *
     * Example Error Response:
     * - Redirects back with an error message: "An error occurred while changing the password. Please try again."
     */

    public function changePassword(ChangePasswordRequest $request){
        if($request->isMethod('POST')){
            try{
                $data = $request->validated();
                $user = Auth::user();
                $user->password = Hash::make($data['new_password']);
                $user->save();

                return redirect()->back()->with('success', 'Password changed successfully');
            }
            catch(Exception $e){
                // Log the error for debugging
                \Log::error('Error changing password: ' . $e->getMessage());
                return redirect()->back()->with('error', 'An error occurred while changing the password. Please try again.');
            }
            
        }
        return view('setting.changePassword');
    }

    public function updateProfile(){

    }
}
