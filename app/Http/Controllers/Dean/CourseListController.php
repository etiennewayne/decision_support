<?php

namespace App\Http\Controllers\Dean;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use Auth;


class CourseListController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('dean.course-list');
    }


    public function getCourseList(Request $req){
        $user = Auth::user();
        $course = $req->course;

        $sort = explode('.', $req->sort_by);

        $data = Schedule::with(['course', 'institute', 'acadyear', 'program', 'room', 'faculty'])
            ->where('institute_id', $user->institute_id)

            ->whereHas('course', function($q) use ($course) {
                $q->where('course_code', 'like', $course . '%');
            })
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $data;
    }

}
