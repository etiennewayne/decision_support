<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AcademicYear;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\Room;

use App\Rules\DetectConflictRule;


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

        $data = Schedule::with('acadyear', 'program', 'course', 'room')
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
        $rooms = Room::orderBy('room', 'asc')->get();

        return view('cpanel.schedules.schedules-create-edit')
            ->with('acadYears', $acadYears)
            ->with('programs', $programs)
            ->with('rooms', $rooms);
    }

    public function store(Request $req){

        $ayid = $req->acadyear_id;

        $mon = $req->mon == true ? 1 : 0;
        $tue = $req->tue == true ? 1 : 0;
        $wed = $req->wed == true ? 1 : 0;
        $thu = $req->thu == true ? 1 : 0;
        $fri = $req->fri == true ? 1 : 0;
        $sat = $req->sat == true ? 1 : 0;
        $sun = $req->sun == true ? 1 : 0;


        $startTime = date("H:i:s", strtotime($req->start_time)); //convert to date format UNIX
        $endTime = date("H:i:s", strtotime($req->end_time)); //convert to date format UNIX

        $req->validate([
            'acadyear_id' => ['required'],
            'program_id' => ['required'],
            'course_id' => ['required'],
            'room_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'start_time' => [new DetectConflictRule($ayid, $startTime, $endTime, $mon, $tue, $wed, $thu, $fri, $sat, $sun)]
        ], $message = [
            'acadyear_id.required' => 'Academic year is required.',
            'program_id.required' => 'Program is required.',
            'course_id.required' => 'Course is required.',
            'room_id.required' => 'Room is required.',
        ]);


        //check if input time have no conflict to other
        // $countExist = Schedule::with('acadyear')
        //     ->whereHas('acadyear', function($q) use ($ayid){
        //         $q->where('acadyear_id', $ayid);
        //     })
        //     ->where(function($query) use ($startTime, $endTime){
        //         $query->whereBetween('start_time', [$startTime, $endTime])
        //             ->orWhereBetween('end_time', [$startTime, $endTime]);
        //     });
        
        // if($mon == 1 || $tue == 1 || $wed == 1 || $thu == 1 || $fri == 1 || $sat == 1 || $sun == 1){
        //     $countExist->where(function($q) use ($mon, $tue,$wed, $thu, $fri, $sat, $sun){
        //         $mon == 1 ? $q->orWhere('mon', 1) : '';
        //         $tue == 1 ? $q->orWhere('tue', 1) : '';
        //         $wed == 1 ? $q->orWhere('wed', 1) : '';
        //         $thu == 1 ? $q->orWhere('thu', 1) : '';
        //         $fri == 1 ? $q->orWhere('fri', 1) : '';
        //         $sat == 1 ? $q->orWhere('sat', 1) : '';
        //         $sun == 1 ? $q->orWhere('sun', 1): '';
    
        //     });
        // }
        // $count = $countExist->count();

        if($count > 0){
            return response()->json([
                'errors' => [
                    'exist' =>  ['Schedule already exist.']
                ]
            ], 422);
        }

        return $count;


        Schedule::create([
            'acadyear_id' => $ayid,
            'program_id' => $req->program_id,
            'course_id' => $req->course_id,
            'room_id' => $req->room_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'mon' => $mon,
            'tue' => $tue,
            'wed' => $wed,
            'thu' => $thu,
            'fri' => $fri,
            'sat' => $sat,
            'sun' => $sun,

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
        $rooms = Room::orderBy('room', 'asc')->get();


        $data = Schedule::with('acadyear', 'program', 'course')
            ->where('schedule_id', $id)
            ->first();


        //return $data;

        return view('cpanel.schedules.schedules-create-edit')
            ->with('acadYears', $acadYears)
            ->with('programs', $programs)
            ->with('rooms', $rooms)
            ->with('data', $data);
    }


    public function update(Request $req, $id){

        $ayid = $req->acadyear_id;

        $startTime = date("H:i:s", strtotime($req->start_time)); //convert to date format UNIX
        $endTime = date("H:i:s", strtotime($req->end_time)); //convert to date format UNIX

        $req->validate([
            'acadyear_id' => ['required'],
            'program_id' => ['required'],
            'course_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'room_id' => ['required'],
        ], $message = [
            'acadyear_id.required' => 'Academic year is required.',
            'program_id.required' => 'Program is required.',
            'course_id.required' => 'Course is required.',
            'room_id.required' => 'Room is required.',

        ]);

        $data = Schedule::find($id);
        $data->acadyear_id = $ayid;
        $data->program_id = $req->program_id;
        $data->course_id = $req->course_id;
        $data->room_id = $req->room_id;
        $data->start_time = $startTime;
        $data->end_time = $endTime;
        $data->mon = $req->mon;
        $data->tue = $req->tue;
        $data->wed = $req->wed;
        $data->thu = $req->thu;
        $data->fri = $req->fri;
        $data->sat = $req->sat;
        $data->sun = $req->sun;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }


    public function destroy($id){
        Schedule::destroy($id);

        return response()->json([
            'status' => 'delted'
        ], 200);
    }


}
