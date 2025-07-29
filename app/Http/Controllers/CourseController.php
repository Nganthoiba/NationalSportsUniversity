<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Department;

use App\Http\Requests\AddCourseRequest;

class CourseController extends Controller
{
    public function listCourses(Request $request, string $short_name = null){
        $coursList = !is_null($short_name)?Course::where('short_form', $short_name)->get():Course::all();
                
        $filtered = array_filter($coursList->toArray(), function($course) {
            // Filter out courses that are soft deleted
            return !isset($course['deleted_at']) || is_null($course['deleted_at']);
        });
        
        $courses = (object) array_map(function($course) {
            // For each course, fetch the associated department
            $course['department'] = Department::find($course['department_id']);
            return $course;
        }, $filtered);
        $courses = json_decode(json_encode($courses));
        
        // If the request is an AJAX request or expects JSON, return a JSON response        
        if($request->wantsJson()){
            return response()->json([
                'courses' => $courses
            ]);
        }
        return view('courses.list', [
            'courses' => $courses
        ]);    
    }

    public function addCourse(AddCourseRequest $request){
        //Check if the request method is POST
        if($request->isMethod('post')){
            $request->validated();
            //Create a new course instance and save it to the database
            $course = new Course();
            try{ 
                //Handle any exceptions that may occur during the save operation       
                $course->course_name = request('course_name');
                $course->short_form = request('short_form');
                $course->course_in_hindi = request('course_in_hindi');
                $course->department_id = request('department_id');
                $course->enabled = true; //By default
                $course->save();
            }
            catch(Exception $e){
                if($request->wantsJson()){
                    return response()->json([
                        'message' => 'Error adding course: ' . $e->getMessage()
                    ], 500);
                }
                return redirect()->back()->with([
                    'error' => 'Error adding course: ' . $e->getMessage()
                ]);

            }          
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'Course added successfully',
                    'course' => $course
                ]);
            }
            return redirect()->back()->with([
                'success' => 'Course added successfully'
            ]);
        }

        // Display a view for creating a course
        $departments = Department::all();
        return view('courses.create', ['departments' => $departments]);
    }

    //Method to update a course
    public function updateCourse(Request $request, string $id){
        $course = Course::find($id);
        if(!$course){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'Course not found'
                ], 404);
            }
            return redirect()->back()->with([
                'error' => 'Course not found',
                'course' => $course
            ]);
        }

        if($request->isMethod('get')){
            // Display a view for editing the course
            $departments = Department::all();
            return view('courses.edit', [
                'course' => $course,
                'departments' => $departments
            ]);
        }
        
        $course->course_name = request('course_name', $course->course_name);
        $course->short_form = request('short_form', $course->short_form);
        $course->course_in_hindi = request('course_in_hindi', $course->course_in_hindi);
        $course->department_id = request('department_id', $course->department_id);
        $course->save();

        if($request->wantsJson()){
            return response()->json([
                'message' => 'Course updated successfully',
                'course' => $course
            ]);
        }
        // Redirect back with success message
        return redirect()->back()->with([
            'success' => 'Course updated successfully',
            'course' => $course
        ]);
        
    }

    //Method to delete a course ( soft delete )
    public function deleteCourse(string $id){
        $course = Course::find($id);
        if(!$course){
            return response()->json([
                'message' => 'Course not found'
            ], 404);
        }
        $course->delete_at = now(); // Soft delete
        $course->save();
        // If the request is an AJAX request or expects JSON, return a JSON response
        if(request()->wantsJson()){
            return response()->json([
                'message' => 'Course deleted successfully'
            ]);
        }

        // Redirect back with success message
        return redirect()->back()->with([
            'success' => 'Course deleted successfully']);
    }

    //Enpoint to enable or disable a course instead of delete/restore
    public function enableOrDisable(Request $request){
        $request->validate([
            'id' => 'required', //Course id
            'flag' => 'required|boolean' // if true it means enabled otherwise disabled
        ]);

        $course = Course::find($request->id);
        if(!$course){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'Course not found'
                ], 404);
            }
            return redirect()->back()->with([
                'error' => 'Course not found',
                'course' => $course
            ]);
        }

        $course->enabled = $request->flag;
        try{
            $course->save();

            $message = $request->flag?"Cousrse has been enabled successfully":"Cousrse has been disabled successfully";
            if(request()->wantsJson()){
                return response()->json([
                    'message' => $message
                ]);
            }

            // Redirect back with success message
            return redirect()->back()->with([
                'success' => $message]);

        }catch(Exception $e){
            if(request()->wantsJson()){
                return response()->json([
                    'message' => "Sorry, we have failed to process your request."
                ]);
            }

            // Redirect back with success message
            return redirect()->back()->with([
                'error' => "Sorry, we have failed to process your request."]);

        }
    }
    
}
