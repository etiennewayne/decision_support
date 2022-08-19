<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    //

    public function index(){
        return view('cpanel.academicyear.academic-year');
    }


    public function getAcadYears(Request $req){
        $sort = explode('.', $req->sort_by);
        return AcademicYear::orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);
    }

    public function show($id){
        return AcademicYear::find($id);
    }

    public function store(Request $req){

        $req->validate([
            'code' => ['required', 'unique:academic_years'],
            'semester' => ['required']
        ]);

        AcademicYear::create([
            'code' => strtoupper($req->code),
            'semester' => strtoupper($req->semester),
            'acadyear_desc' => strtoupper($req->acadyear_desc),
            'active' => 1,
        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }

    public function update(Request $req, $id){
        $req->validate([
            'code' => ['required', 'unique:academic_years,code,'.$id.',acadyear_id'],
            'semester' => ['required']
        ]);

        $data = AcademicYear::find($id);
        $data->code = strtoupper($req->code);
        $data->semester = strtoupper($req->semester);
        $data->acadyear_desc = strtoupper($req->acadyear_desc);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        AcademicYear::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
