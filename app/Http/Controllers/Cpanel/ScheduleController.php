<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AcademicYear;
use App\Models\Program;

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


    public function getAllData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Program::orderBy('program_code', 'asc')
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

        $req->validate([
            'program_code' => ['required', 'unique:programs'],
            'program_desc' => ['required']
        ]);

        Program::create([
            'program_code' => strtoupper($req->program_code),
            'program_desc' => strtoupper($req->program_desc),

        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

}
