<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('cpanel.courses.courses');
    }


    public function getCourses(Request $req){
        $sort = explode('.', $req->sort_by);

        return Course::orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }


}
