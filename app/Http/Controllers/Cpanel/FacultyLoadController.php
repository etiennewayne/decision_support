<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AcademicYear;

class FacultyLoadController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();

        return view('cpanel.faculty.faculty-load')
            ->with('acadYears', $acadYears);
    }

    public function show($id){
        return FacultyLoad::find($id);
    }

    public function getFacultyLoad(Request $req){

    }


}
