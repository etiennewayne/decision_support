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


Route::resource('/cpanel/programs', App\Http\Controllers\Cpanel\ProgramController::class);
Route::get('/cpanel/get-programs', [App\Http\Controllers\Cpanel\ProgramController::class, 'getAllData']);

Route::resource('/cpanel/courses', App\Http\Controllers\Cpanel\CourseController::class);
Route::get('/cpanel/get-courses', [App\Http\Controllers\Cpanel\CourseController::class, 'getCourses']);



Route::resource('/cpanel/users', App\Http\Controllers\Cpanel\UserController::class);
Route::get('/cpanel/get-users', [App\Http\Controllers\Cpanel\UserController::class, 'getUsers']);


Route::get('/get-open-semesters', function(){
    return \App\Models\Semester::all();
});
Route::get('/get-open-course-types', function(){
    return \App\Models\CourseType::all();
});





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
