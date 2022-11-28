<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Schedule;
use Illuminate\Http\Request;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

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



        $data = DB::table('schedules as a')
            ->join('faculty as b', 'a.faculty_id', 'b.faculty_id')
            ->join('courses as c', 'a.course_id', 'c.course_id')
            ->join('academic_years as d', 'a.acadyear_id', 'd.acadyear_id')
            ->where('a.acadyear_id', $req->ayid)
            ->where('a.faculty_id', $req->fid)
            ->get();

        return $data;
    }

}

