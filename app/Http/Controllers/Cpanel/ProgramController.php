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

    public function show($id){
        return Program::find($id);
    }

    public function getAllData(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Program::where('program_code', 'like', $req->programcode . '%')
            ->orderBy('program_code', 'asc')
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

    public function update(Request $req, $id){
        $req->validate([
            'program_code' => ['required', 'unique:programs,program_code,' .$id. ',program_id'],
            'program_desc' => ['required']
        ]);

        $data = Program::find($id);

        $data->program_code = strtoupper($req->program_code);
        $data->program_desc = strtoupper($req->program_desc);
        $data->save();
        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        Program::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);

    }

}
