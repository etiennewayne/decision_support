<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultyLoadController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        return view('cpanel.faculty.faculty-load');
    }

    public function show($id){
        return FacultyLoad::find($id);
    }

    public function getFacultyLoad(Request $req){

    }


}
