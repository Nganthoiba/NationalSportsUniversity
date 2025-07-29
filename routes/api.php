<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::post('/test', function () {
    return response()->json(['message' => 'CSRF skipped']);
});
Route::group(['prefix'=>'student'], function(){
    Route::get('/list', [StudentController::class, 'getStudents'])->name('api.getStudents');
    Route::delete('/delete/{studentId}', [StudentController::class, 'deleteStudent'])->name('api.deleteStudent');
});