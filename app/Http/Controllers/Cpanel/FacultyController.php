<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Faculty;


class FacultyController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('cpanel.faculty.faculty'); //blade.php
    }

    public function show($id){
        return Faculty::find($id);
    }

    public function getFaculty(Request $req)
    {
        $sort = explode('.', $req->sort_by);
        $data = Faculty::where('lname', 'like', $req->lname . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
        return $data;
    }

    public function store(Request $req){

        $req->validate([
            'lname' => ['required', 'max: 100'],
            'fname' => ['required', 'max: 100'],
            'sex' => ['required', 'max: 10'],
        ]);

        Faculty::create([
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => strtoupper($req->mname),
            'sex' => strtoupper($req->sex),
            'active' => $req->active ? 1 : 0,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){

        $req->validate([
            'lname' => ['required', 'max: 100'],
            'fname' => ['required', 'max: 100'],
            'sex' => ['required', 'max: 10'],
        ]);

        $data = Faculty::find($id);

        $data->lname = strtoupper($req->lname);
        $data->fname = strtoupper($req->fname);
        $data->mname = strtoupper($req->mname);
        $data->sex = strtoupper($req->sex);
        $data->active = $req->active ? 1 : 0;
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        Faculty::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
