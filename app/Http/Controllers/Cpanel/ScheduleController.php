<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Rules\ConflictFacultyRule;
use App\Rules\FacultyMaxUnitRule;
use App\Rules\FacultyPreparationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\AcademicYear;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\Room;
use App\Models\User;

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

        $scheduleid = '';

        if ($req->has('scheduleid')) {
            //if request has schedule id
            $scheduleid = $req->scheduleid;
        }

        $data = Schedule::with('acadyear', 'program', 'course', 'room', 'faculty', 'institute')
            ->whereHas('acadyear', function($q) use ($aycode){
                $q->where('code', $aycode);
            })
            ->whereHas('course', function($q) use ($course){
                $q->where('course_code', 'like', $course . '%')
                    ->orWhere('course_desc', 'like', $course . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }


    public function getSchedulesForEnrolment(Request $req){
        //return $req;

        $sort = explode('.', $req->sort_by);
        $ayid = $req->ayid;
        $course = $req->course;

        $scheduleid = '';

        if ($req->has('scheduleid')) {
            //if request has schedule id
            $scheduleid = $req->scheduleid;
        }

        $data = Schedule::with('acadyear', 'program', 'course', 'room', 'faculty', 'institute')
            ->whereHas('acadyear', function($q) use ($ayid){
                $q->where('acadyear_id', $ayid);
            })
            ->whereHas('course', function($q) use ($course){
                $q->where('course_code', 'like', $course . '%')
                    ->orWhere('course_desc', 'like', $course . '%');
            })
            ->paginate($req->perpage);
        return $data;
    }

    public function getRecommendedFaculty(Request $req){
        $sort = explode('.', $req->sort_by);

        $isLoadAll = $req->isloadall;
        //return $isLoadAll;
        $courseid = $req->courseid;

        if($isLoadAll > 0){
            $data = Faculty::where('active', 1)
                ->paginate($req->perpage);
        }else{
            $data = DB::table('schedules as a')
                ->join('faculty as b', 'a.faculty_id', 'b.faculty_id')
                ->where('a.course_id', $courseid)
                ->select('a.schedule_id', 'b.lname', 'b.fname', 'b.faculty_id', 'b.mname',
                    DB::raw('(count(b.faculty_id)) as count_teaching'),
                    'b.sex'
                )
                ->orderBy('count_teaching', 'desc')
                ->groupBy('b.faculty_id')
                ->paginate($req->perpage);
        }

        return $data;
    }

    public function create(){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();
        $programs = Program::orderBy('program_code', 'asc')
            //->where('is_admin', 0)
            ->get();
        $rooms = Room::orderBy('room', 'asc')->get();

        return view('cpanel.schedules.schedules-create-edit')
            ->with('acadYears', $acadYears)
            ->with('programs', $programs)
            ->with('rooms', $rooms);
    }

    public function store(Request $req){

        $forceSave = $req->forcesave;

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

        if($forceSave == 'Yes'){
            $req->validate([
                'acadyear_id' => ['required'],
                'institute_id' => ['required'],
                'program_id' => ['required'],
                'course_id' => ['required'],
                'room_id' => ['required'],
                'start_time' => ['required'],
                'end_time' => ['required'],
            ], $message = [
                'acadyear_id.required' => 'Academic year is required.',
                'program_id.required' => 'Program is required.',
                'course_id.required' => 'Course is required.',
                'room_id.required' => 'Room is required.',
            ]);

        }else{

            $req->validate([
                'acadyear_id' => ['required'],
                'institute_id' => ['required'],
                'program_id' => ['required'],
                'course_id' => ['required'],
                'room_id' => ['required'],
                'start_time' => ['required'],
                'end_time' => ['required'],
                'schedule' => [new DetectConflictRule($ayid, $startTime, $endTime, $mon, $tue, $wed, $thu, $fri, $sat, $sun, $req->room_id,0)]
            ], $message = [
                'acadyear_id.required' => 'Academic year is required.',
                'program_id.required' => 'Program is required.',
                'course_id.required' => 'Course is required.',
                'room_id.required' => 'Room is required.',
            ]);
        }


        Schedule::create([
            'acadyear_id' => $ayid,
            'institute_id' => $req->institute_id,
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
            //->where('is_admin', 0)
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


        $mon = $req->mon == true ? 1 : 0;
        $tue = $req->tue == true ? 1 : 0;
        $wed = $req->wed == true ? 1 : 0;
        $thu = $req->thu == true ? 1 : 0;
        $fri = $req->fri == true ? 1 : 0;
        $sat = $req->sat == true ? 1 : 0;
        $sun = $req->sun == true ? 1 : 0;


        $req->validate([
            'acadyear_id' => ['required'],
            'institute_id' => ['required'],
            'program_id' => ['required'],
            'course_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'room_id' => ['required'],
            'schedule' => [new DetectConflictRule($ayid, $startTime, $endTime, $mon, $tue, $wed, $thu, $fri, $sat, $sun, $req->room_id, $id)]
        ], $message = [
            'acadyear_id.required' => 'Academic year is required.',
            'program_id.required' => 'Program is required.',
            'course_id.required' => 'Course is required.',
            'room_id.required' => 'Room is required.',

        ]);

        $data = Schedule::find($id);
        $data->acadyear_id = $ayid;
        $data->institute_id = $req->institute_id;
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

    public function getConflictData(Request $req){
        return $req;

        $ayid = $req->acadyear_id;

        $mon = $req->mon ? 1 : 0;
        $tue = $req->tue ? 1 : 0;
        $wed = $req->wed ? 1 : 0;
        $thu = $req->thu ? 1 : 0;
        $fri = $req->fri ? 1 : 0;
        $sat = $req->sat ? 1 : 0;
        $sun = $req->sun ? 1 : 0;


        $startTime = date("H:i:s", strtotime($req->start_time)); //convert to date format UNIX
        $endTime = date("H:i:s", strtotime($req->end_time)); //convert to date format UNIX

        $countExist = Schedule::with('acadyear', 'course', 'program', 'room')
            ->whereHas('acadyear', function($q) use ($ayid){
                $q->where('acadyear_id', $ayid);
            })
            ->where(function($query) use ($startTime, $endTime){
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime]);
            });

        if($mon == 1 || $tue == 1 || $wed == 1 || $thu == 1 || $fri == 1 || $sat == 1 || $sun == 1){
            $countExist->where(function($q) use ($mon, $tue,$wed, $thu, $fri, $sat, $sun){
                $mon == 1 ? $q->orWhere('mon', 1) : '';
                $tue == 1 ? $q->orWhere('tue', 1) : '';
                $wed == 1 ? $q->orWhere('wed', 1) : '';
                $thu == 1 ? $q->orWhere('thu', 1) : '';
                $fri == 1 ? $q->orWhere('fri', 1) : '';
                $sat == 1 ? $q->orWhere('sat', 1) : '';
                $sun == 1 ? $q->orWhere('sun', 1): '';
            });
        }

        $data = $countExist->get();

        return $data;
    }

    public function saveFaculty(Request $req){
        //return $req;
        $acadyear = AcademicYear::where('active', 1)->first(); //get current acadyear
        
        //get //all data of a schedule
        $data = Schedule::with(['course'])
            ->find($req->schedule_id);

        $ayid = $data->acadyear_id;
        $startTime = $data->start_time;
        $endTime = $data->end_time;
        $mon = $data->mon;
        $tue = $data->tue;
        $wed = $data->wed;
        $thu = $data->thu;
        $fri = $data->fri;
        $sat = $data->sat;
        $sun = $data->sun;

        $req->validate([
            'faculty_id' => ['required', new ConflictFacultyRule($ayid, $startTime, $endTime, $mon, $tue, $wed, $thu, $fri, $sat, $sun)
                , new FacultyPreparationRule($acadyear), new FacultyMaxUnitRule($acadyear, $req->schedule_id)],
        ], $message = [
            'faculty_id.required' => 'Please select faculty.'
        ]);

        $data->faculty_id = $req->faculty_id;
        $data->save();

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function removeFaculty($id){

        $data = Schedule::find($id);
        $data->faculty_id = 0;
        $data->save();

        return response()->json([
            'status' => 'removed'
        ], 200);
    }

    public function destroy($id){
        Schedule::destroy($id);

        return response()->json([
            'status' => 'delted'
        ], 200);
    }


}
