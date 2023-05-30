<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Faculty;

class ReportFacultyLoadController extends Controller
{
    //

    public function index(){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();
        return view('cpanel.report.report-faculty-load')
            ->with('acadYears', $acadYears);
    }

    public function getAllFacultyLoad(Request $req){
        //$ayid = $req->ayid;

        $data = Faculty::with(['schedules.acadyear', 'schedules.faculty', 'schedules.course'])
            ->whereHas('schedules', function($q) use ($req) {
                $q->where('acadyear_id', $req->ayid);
            })->get();

        return $data;
    }

}
