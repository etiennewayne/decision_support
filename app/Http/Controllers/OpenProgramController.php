<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;

class OpenProgramController extends Controller
{
    //

    public function getPrograms($insId){
        return Program::where('institute_id', $insId)->get();
    }
}
