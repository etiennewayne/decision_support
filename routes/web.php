<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // if(Auth::check()){
    //     $user = Auth::user();
    //     return view('welcome')
    //         ->with('user', $user->only(['lname', 'fname', 'mname', 'suffix', 'role', 'remark', 'office_id']));
    // }
    return view('welcome');
});


Route::get('/get-user', function () {
    //get user if authenticated
    if(Auth::check()){
        $user = Auth::user();
        return $user;
    }
});


Route::get('/get-dental-services', [App\Http\Controllers\Administrator\ServicesController::class, 'getDentalServices']);


Auth::routes([
    'login' => 'false'
]);


Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::get('/sample',[App\Http\Controllers\SampleController::class,'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/sign-up', [App\Http\Controllers\SignUpController::class, 'index']);

Route::post('/sign-up', [App\Http\Controllers\SignUpController::class, 'store']);



//ADDRESS
Route::get('/load-provinces', [App\Http\Controllers\AddressController::class, 'loadProvinces']);
Route::get('/load-cities', [App\Http\Controllers\AddressController::class, 'loadCities']);
Route::get('/load-barangays', [App\Http\Controllers\AddressController::class, 'loadBarangays']);




/*     ADMINSITRATOR          */
Route::resource('/cpanel/dashboard', App\Http\Controllers\Cpanel\CpanelDashboardController::class);


Route::resource('/cpanel/acad-years', App\Http\Controllers\Cpanel\AcademicYearController::class);
Route::get('/cpanel/get-acad-years', [App\Http\Controllers\Cpanel\AcademicYearController::class, 'getAcadYears']);
Route::post('/cpanel/set-active-ay/{id}', [App\Http\Controllers\Cpanel\AcademicYearController::class, 'setActive']);


Route::resource('/cpanel/rooms', App\Http\Controllers\Cpanel\RoomController::class);
Route::get('/cpanel/get-rooms', [App\Http\Controllers\Cpanel\RoomController::class, 'getRooms']);



Route::resource('/cpanel/programs', App\Http\Controllers\Cpanel\ProgramController::class);
Route::get('/cpanel/get-programs', [App\Http\Controllers\Cpanel\ProgramController::class, 'getAllData']);

Route::resource('/cpanel/courses', App\Http\Controllers\Cpanel\CourseController::class);
Route::get('/cpanel/get-courses', [App\Http\Controllers\Cpanel\CourseController::class, 'getCourses']);

//get courses filter by course code and course desc
//use in ModalCourse
Route::get('/cpanel/get-browse-courses', [App\Http\Controllers\Cpanel\CourseController::class, 'getBrowseCoursesForModal']);
//for modal

Route::resource('/cpanel/users', App\Http\Controllers\Cpanel\UserController::class);
Route::get('/cpanel/get-users', [App\Http\Controllers\Cpanel\UserController::class, 'getUsers']);


Route::resource('/cpanel/schedules', App\Http\Controllers\Cpanel\ScheduleController::class);
Route::get('/cpanel/get-schedules', [App\Http\Controllers\Cpanel\ScheduleController::class, 'getSchedule']);
Route::get('/cpanel/get-schedules-for-enrolment', [App\Http\Controllers\Cpanel\ScheduleController::class, 'getSchedulesForEnrolment']);
Route::get('/cpanel/get-conflict-data', [App\Http\Controllers\Cpanel\ScheduleController::class, 'getConflictData']);
Route::get('/cpanel/get-recommended-faculty', [App\Http\Controllers\Cpanel\ScheduleController::class, 'getRecommendedFaculty']);


Route::resource('/cpanel/enrolment', App\Http\Controllers\Cpanel\EnrolmentController::class);
Route::get('/cpanel/get-student-enrollees', [App\Http\Controllers\Cpanel\EnrolmentController::class, 'getStudentEnrollees']);
Route::get('/cpanel/get-students', [App\Http\Controllers\Cpanel\EnrolmentController::class, 'getStudents']);


Route::post('/cpanel/save-faculty', [App\Http\Controllers\Cpanel\ScheduleController::class, 'saveFaculty']);
Route::post('/cpanel/remove-faculty/{id}', [App\Http\Controllers\Cpanel\ScheduleController::class, 'removeFaculty']);


Route::resource('/cpanel/faculty', App\Http\Controllers\Cpanel\FacultyController::class);
Route::get('/cpanel/get-faculty', [App\Http\Controllers\Cpanel\FacultyController::class, 'getFaculty']);
//Route::get('/cpanel/get-faculty', [App\Http\Controllers\Cpanel\FacultyController::class, 'getFaculty']);


Route::resource('/cpanel/faculty-load', App\Http\Controllers\Cpanel\FacultyLoadController::class);
Route::get('/cpanel/get-faculty-load', [App\Http\Controllers\Cpanel\FacultyLoadController::class, 'getFacultyLoad']);




//////DEAAN
Route::resource('/dean/dashboard', App\Http\Controllers\Dean\DeanHomeController::class);

Route::get('/cpanel/course-list', [App\Http\Controllers\Dean\CourseListController::class, 'index']);
Route::get('/cpanel/get-course-list', [App\Http\Controllers\Dean\CourseListController::class, 'getCourseList']);










Route::get('/get-open-semesters', function(){
    return \App\Models\Semester::all();
});
Route::get('/get-open-course-types', function(){
    return \App\Models\CourseType::all();
});


Route::get('/get-open-institutes', [App\Http\Controllers\OpenInstituteController::class, 'getOpenInstitutes']);
Route::get('/get-open-programs/{insId}', [App\Http\Controllers\OpenProgramController::class, 'getPrograms']);


//Offices Administrator (For office management)
/*     ADMINSITRATOR          */


Route::get('/session', function(){
    return Session::all();
});


Route::get('/applogout', function(Request $req){
    \Auth::logout();
    $req->session()->invalidate();
    $req->session()->regenerateToken();


});
