<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use App\Models\EnrolmentDetail;
use Illuminate\Http\Request;

use App\Models\AcademicYear;
use App\Models\Enrolment;
use App\Models\Program;
use App\Models\Student;


use Illuminate\Support\Facades\DB;

class EnrolmentController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }


    public function index(){
        $acadyears = AcademicYear::orderBy('code', 'asc')->get();

        return view('cpanel.enrolment.enrolment')
            ->with('acadyears', $acadyears);
    }


    public function getStudents(Request $req){
        $sort = explode('.', $req->sort_by);
        $data = Student::with(['program'])->orderBy('lname', 'asc')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);;
        return $data;

    }

    public function getStudentEnrollees(Request $req){
        $sort = explode('.', $req->sort_by);

        // $data = DB::table('student_enrolments as a')
        //     ->join('academic_years as b', 'a.acadyear_id', 'b.acadyear_id')
        //     ->join('students as c', 'a.student_id', 'c.student_id')
        //     ->join('programs as d', 'a.program_id', 'd.program_id')
        //     ->join('schedules as e', 'a.schedule_id', 'e.schedule_id')
        //     ->join('courses as f', 'e.course_id', 'f.course_id')
        //     ->select('a.enrolment_id', 'a.acadyear_id', 'a.student_id', 'a.program_id as enrolment_program_id', 'a.schedule_id',
        //         'b.code', 'b.semester', 'b.acadyear_desc',
        //         'c.student_ref', 'c.lname', 'c.fname', 'c.mname', 'c.sex', 'c.program_id as student_program_id',
        //         'd.program_code', 'd.program_desc',
        //         'e.course_id', 'e.room_id', 'e.program_id as schedule_program_id', 'e.start_time', 'e.end_time',
        //         'f.course_code', 'f.course_desc'
        //     )
        //     ->orderBy($sort[0], $sort[1])
        //     ->paginate($req->perpage);

        $data = Enrolment::with(['academic_year', 'student', 'program', 'enrolment_details'])
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }




    public function create(){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();
        $programs = Program::orderBy('program_code', 'asc')
            //->where('is_admin', 0)
            ->get();
        return view('cpanel.enrolment.enrolment-create-edit')
            ->with('acadYears', $acadYears)
            ->with('programs', $programs);
    }



    public function store(Request $req){

        $data = Enrolment::create([
            'acadyear_id' => $req->acadyear_id,
            'student_id' => $req->student_id,
            'program_id' => $req->program_id,
        ]);

        foreach ($req->enrolment_details as $sched){
            EnrolmentDetail::create([
                'enrolment_id' => $data->enrolment_id,
                'schedule_id' => $sched['schedule_id']
            ]);
        }

        return response()->json([
            'status' => 'saved'
        ], 200);

    }

    public function edit($id){
        $acadYears = AcademicYear::orderBy('code', 'asc')->get();
        $programs = Program::orderBy('program_code', 'asc')
            //->where('is_admin', 0)
            ->get();
        return view('cpanel.enrolment.enrolment-create-edit')
            ->with('acadYears', $acadYears)
            ->with('programs', $programs);
    }

    public function destroy($id){
        Enrolment::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
