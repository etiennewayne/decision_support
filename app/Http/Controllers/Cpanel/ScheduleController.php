<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AcademicYear;
use App\Models\Program;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    //


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();

        return view('cpanel.schedules.schedules')
            ->with('acadYears', $acadYears); //blade.php
    }


    public function getSchedule(Request $req){
        $sort = explode('.', $req->sort_by);
        $aycode = $req->aycode;
        $course = $req->course;
    
        $data = Schedule::with('acadyear', 'program', 'course')
            ->whereHas('acadyear', function($q) use ($aycode){
                $q->where('code', 'like', $aycode . '%');
            })
            ->whereHas('course', function($q) use ($course){
                $q->where('course_code', 'like', $course . '%')
                    ->orWhere('course_desc', 'like', $course . '%');
            })
            ->paginate($req->perpage);
        return $data;
    }

    public function create(){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();
        $programs = Program::orderBy('program_code', 'asc')
            ->where('is_admin', 0)
            ->get();

        return view('cpanel.schedules.schedules-create-edit')
            ->with('acadYears', $acadYears)
            ->with('programs', $programs);
    }

    public function store(Request $req){
        $ayid = $req->acadyear_id;

        $startTime = date("H:i:s", strtotime($req->start_time)); //convert to date format UNIX
        $endTime = date("H:i:s", strtotime($req->end_time)); //convert to date format UNIX

        $req->validate([
            'acadyear_id' => ['required'],
            'program_id' => ['required'],
            'course_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ], $message = [
            'acadyear_id.required' => 'Academic year is required.',
            'program_id.required' => 'Program is required.',
            'course_id.required' => 'Course is required.',
        ]);


        //check if input time have no conflict to other
        $countExist = Schedule::with('acadyear')
            ->whereHas('acadyear', function($q) use ($ayid){
                $q->where('acadyear_id', $ayid);
            })
            ->where(function($query) use ($startTime, $endTime){
            $query->whereBetween('start_time', [$startTime, $endTime])
                ->orWhereBetween('end_time', [$startTime,$endTime]);
        })->count();

        
        if($countExist > 0){
            return response()->json([
                'errors' => [
                    'exist' =>  ['Schedule already exist.']
                ]
            ], 422);
        }
       
        return $countExist;


        Schedule::create([
            'acadyear_id' => $ayid,
            'program_id' => $req->program_id,
            'course_id' => $req->course_id,

            'start_time' => $startTime,
            'end_time' => $endTime,
            'mon' => $req->mon,
            'tue' => $req->tue,
            'wed' => $req->wed,
            'thu' => $req->thu,
            'fri' => $req->fri,
            'sat' => $req->sat,
            'sun' => $req->sun,

        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function edit($id){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();
        $programs = Program::orderBy('program_code', 'asc')
            ->where('is_admin', 0)
            ->get();

        $data = Schedule::with('acadyear', 'program', 'course')
            ->where('schedule_id', $id)
            ->first();


        return $data;

        return view('cpanel.schedules.schedules-create-edit')
            ->with('acadYears', $acadYears)
            ->with('programs', $programs);
    }


    public function update(Request $req, $id){}


    public function destroy($id){
        Schedule::destroy($id);

        return response()->json([
            'status' => 'delted'
        ], 200);
    }


}
