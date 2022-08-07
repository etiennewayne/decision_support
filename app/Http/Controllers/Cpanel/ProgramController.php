<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Program;

class ProgramController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('cpanel.program'); //blade.php
    }


    public function getAllData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Program::orderBy('program_code', 'asc')
            ->paginate($req->perpage);
        return $data;
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
