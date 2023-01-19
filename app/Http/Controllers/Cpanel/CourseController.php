<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('cpanel.courses.courses');
    }

    public function show($id){
        return Course::find($id);
    }


    public function getCourses(Request $req){
        $sort = explode('.', $req->sort_by);

        $user = Auth::user();


        if($user->role != 'ADMINISTRATOR'){
            $data = Course::where('course_code', 'like', $req->course . '%')
                ->where('institute_id', $user->institute_id)
                ->orderBy($sort[0], $sort[1])
                ->paginate($req->perpage);

        }else{
            $data = Course::where('course_code', 'like', $req->course . '%')
                ->orderBy($sort[0], $sort[1])
                ->paginate($req->perpage);
        }

        return $data;
    }

    public function getBrowseCoursesForModal(Request $req){
        $sort = explode('.', $req->sort_by);

        return Course::where('course_code', 'like', $req->coursecode . '%')
            ->where('course_desc', 'like', $req->coursedesc . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }


    public function store(Request $req){
        $req->validate([
            'institute_id' => ['required'],
            'course_code' => ['required'],
            'course_desc' => ['required'],
            'course_type' => ['required'],
            'course_unit' => ['required'],
        ]);

        Course::create([
            'institute_id' => $req->institute_id,
            'course_code' => strtoupper($req->course_code),
            'course_desc' => strtoupper($req->course_desc),
            'course_type' => strtoupper($req->course_type),
            'course_unit' => strtoupper($req->course_unit),
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){
        $req->validate([
            'institute_id' => ['required'],
            'course_code' => ['required'],
            'course_desc' => ['required'],
            'course_type' => ['required'],
            'course_unit' => ['required'],
        ]);

        $data = Course::find($id);
        $data->institute_id = $req->institute_id;
        $data->course_code = strtoupper($req->course_code);
        $data->course_desc = strtoupper($req->course_desc);
        $data->course_type = strtoupper($req->course_type);
        $data->course_unit = strtoupper($req->course_unit);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){

        Course::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }

}
