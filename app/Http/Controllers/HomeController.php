<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class HomeController extends Controller
{
    public function showDashboard() {
        $currentRole = session('currentRole');
        if($currentRole){

            if($currentRole->role_name == "Super Admin"){
                return view('home.superAdminDashboard');
            }

            $sudentCount = Student::count();
            $dataChangedCount = Student::where('status', 2)->count();
            $approvedCount = Student::where('status', 1)->count();
            $pendingCount = Student::where('status', 0)->count();
            return view('home.dashboard')->with([
                'studentCount' => $sudentCount,
                'approvedCount' => $approvedCount,
                'pendingCount' => $pendingCount,
                'dataChangedCount' => $dataChangedCount,
                'compulsoryFields' => Student::$compulsoryFields,
            ]);
        }

        return view('layout.errorMessage',[
            'title' => 'Unauthorized',
            'message' => "You are unauthorized to access this page because no default role is found in session.",
            'btn_link' => route('logout'),
            'btn_label' => "Go to Login"
        ]);
        
    }

    public function testApi(){
        return response()->json([
            'message' => 'Hello World!'
        ]);
    }
}
