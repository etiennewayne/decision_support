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
Route::resource('/admin-home', App\Http\Controllers\Administrator\AdminHomeController::class);

Route::resource('/users', App\Http\Controllers\Administrator\UserController::class);
Route::get('/get-users', [App\Http\Controllers\Administrator\UserController::class, 'getUsers']);
Route::get('/get-user-offices', [App\Http\Controllers\Administrator\UserController::class, 'getOffices']);


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
