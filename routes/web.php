<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\auth\PasswordResetController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleMappingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
/* use App\Models\Course; */

Route::get('/', function () {
    return view('welcome',[
        'courses' => Course::all()
    ]);
})->name('landing');

Route::get('/generatePDF', [PdfController::class, 'generatePDF'])->name('generatePDF');

Route::get('/home', [HomeController::class, 'showDashboard'])->name('home')->middleware('auth');
Route::get('/dashboard', [HomeController::class, 'showDashboard'])->name('dashboard')->middleware('auth');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/switchRole/{roleId}', [LoginController::class, 'switchRole'])->name('switchRole');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

//Send OTP link
Route::post('/sendOTPLink',  [PasswordResetController::class, 'generateAndSendOTP'])->name('sendOTPLink');
Route::post('/verifyOTPandUpdatePassword',  [PasswordResetController::class, 'verifyOTPSetPassword'])->name('verifyOTPandUpdatePassword');



//User related routes
Route::group(['prefix' => 'users'], function(){

    // Create University Admin Users
    Route::get('/createUniversityAdminUser', [UserController::class, 'createUniversityAdminUser'])->name('users.create-university-admin');
    // Create University Users(staffs)
    Route::get('/createUniversityUser', [UserController::class, 'createUniversityUser'])->name('users.create-university-user');

    Route::post('/createUser', [UserController::class, 'createUser'])->name('users.create')->middleware('auth');

    // Displaying users
    Route::get('/university-admins', [UserController::class, 'getUniversityAdmins'])->name('users.university-admins')->middleware('auth');
    Route::get('/university-users', [UserController::class, 'getUniversityUsers'])->name('users.university-users')->middleware('auth');

    Route::post('/enable-or-disable',[UserController::class, 'enableOrDisable'])->name('users.enableOrDisable')->middleware('auth');
});

Route::get('/forgotPassword', [PasswordResetController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/sendPasswordResetLink', [PasswordResetController::class, 'sendPasswordResetLink'])->name('sendPasswordResetLink');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'resetPassword'])->name('resetPassword');
Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->name('updatePassword');



Route::get('/resetAllPasswords', [UserController::class, 'resetAllPasswords']);
Route::get('/testDB', function() {    

    try {
        $connection = DB::connection();
        dd(DB::connection()->getDatabaseName());
    } catch (\Exception $e) {
        dd($e->getMessage());
    }    
});

Route::group(['prefix'=>'courses'], function(){
    Route::get('/add', [CourseController::class, 'addCourse'])->name('courses.add');
    Route::post('/add', [CourseController::class, 'addCourse']);
    Route::get('/edit/{id?}', [CourseController::class, 'updateCourse'])->name('courses.edit');
    Route::post('/edit/{id}', [CourseController::class, 'updateCourse']);
    Route::get('/delete/{id}', [CourseController::class, 'deleteCourse'])->name('courses.delete');
    Route::get('/list', [CourseController::class, 'listCourses'])->name('courses.list');
    Route::post('/enable-or-disable', [CourseController::class, 'enableOrDisable'])->name('course.EnableOrDisable');
})->middleware('auth');
Route::group(['prefix'=>'student'], function(){
    Route::get('/add', [StudentController::class, 'addNewStudent'])->name('addNewStudent')->middleware('auth');
    Route::post('/add', [StudentController::class, 'addNewStudent'])->middleware('auth');
    /* 
    Route::get('/create', [StudentController::class, 'createStudent'])->name('createStudent');
    Route::post('/create', [StudentController::class, 'createStudent']);
 */
    Route::get('/requestForChange/{studentId}', [StudentController::class, 'requestForChange'])->name('requestForChange')->middleware('auth');
    Route::post('/datachange', [StudentController::class, 'submitRequestForChange'])->name('student.datachange')->middleware('auth');
    Route::post('/cancel-datachange', [StudentController::class, 'cancelDataChange'])->name('student.cancelDataChange')->middleware('auth');

    Route::post('/approve-student', [StudentController::class, 'approveStudent'])->name('approveStudent')->middleware('auth');
    Route::post('/approve-student-data-change', [StudentController::class, 'approveStudentDataChanges'])->name('approveStudentDataChanges')->middleware('auth');
    Route::post('/verify-student-info', [StudentController::class, 'verifyStudentInfo'])->name('student.verifyStudentInfo');
    
    Route::get('/list/{status?}', [StudentController::class, 'displayStudents'])->name('displayStudents')->middleware('auth');
    Route::get('/viewStudent/{studentId}', [StudentController::class, 'viewStudent'])->name('viewStudent')->middleware('auth');
    Route::get('/showStudentDetails/{studentId}', [StudentController::class, 'showStudentDetails'])->name('showStudentDetails');
    Route::get('/showCertificate/{studentId}/{type?}', [StudentController::class, 'showCertificate'])->name('showCertificate');
    Route::get('/data-change-list', [StudentController::class, 'displayDataChange'])->name('displayDataChange')->middleware('auth');
    Route::get('/view-data-change-detail/{id}', [StudentController::class, 'viewDataChangeDetail'])->name('viewDataChangeDetail');
    Route::get('/view-data-change-histories/{id}', [StudentController::class, 'getDataChangeHistories'])->name('getDataChangeHistories');

});

Route::group(['prefix'=>'excel'], function(){
    Route::post('/importStudents', [ExcelController::class, 'importStudents'])->name('excel.importStudents');
});
Route::group(['prefix'=>'settings'], function(){
    Route::get('/',[SettingController::class, 'userSetting'])->name('settings')->middleware('auth');
    Route::get('/changePassword', [SettingController::class, 'changePassword'])->name('setting.changePassword')->middleware('auth');
    Route::post('/changePassword', [SettingController::class, 'changePassword'])->middleware('auth');
});

Route::resource('sports', SportsController::class);
Route::group(['prefix'=>'sports'], function(){
    Route::post('/enable/{sport_id}', [SportsController::class, 'enable'])->name('sports.enable')->middleware('auth');
});

Route::resource('departments', DepartmentController::class);
/**
    * Method	URI	Action	Route Name
    * GET	/departments	index	departments.index
    * GET	/departments/create	create	departments.create
    * POST	/departments	store	departments.store
    * GET	/departments/{department}	show (optional)	departments.show
    * GET	/departments/{department}/edit	edit	departments.edit
    * PUT/PATCH	/departments/{department}	update	departments.update
*/
Route::group(['prefix'=>'departments'], function(){
    Route::post('/enable/{id}', [DepartmentController::class, 'enable'])->name('departments.enable')->middleware('auth');
});

Route::resource('roles', RoleController::class);
Route::group(['prefix'=>'roles'], function(){
    Route::post('/enable/{role_id}', [RoleController::class, 'enable'])->name('roles.enable')->middleware('auth');
});


Route::group(['prefix'=>'userrolemappings'], function(){
    Route::get('/get-assigned-roles/{user_id}', [UserRoleMappingController::class, 'getAssignedRoles'])
    ->name('userrolemappings.getAssignedRoles')
    ->middleware('auth');

    Route::get('/assign-roles', [UserRoleMappingController::class, 'assignRoles'])
    ->name('userrolemappings.assignRoles')
    ->middleware('auth');

    Route::post('/finalize-assignment', [UserRoleMappingController::class,'finalizeAssignment'])
    ->name('userrolemappings.finalizeAssignment')
    ->middleware('auth');
});

Route::resource('permissions', PermissionController::class)->middleware(['auth', 'role:admin']);
Route::group(['prefix'=>'permissions'], function(){
    Route::get('/assignPermissionsToRole/{role_id}', [PermissionController::class, 'assignPermissionsToRole'])->name('permissions.assignPermissionsToRole');
});

Route::group(['prefix'=>'menu'], function(){
    Route::get('/assignMenuRoles', [MenuController::class, 'assignMenuRoles'])->name('menu.assignMenuRoles')->middleware('auth');
    Route::post('/assignMenuRoles', [MenuController::class, 'assignMenuRoles'])->name('menu.createMenuRoleMap')->middleware('auth');
});



