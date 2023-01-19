<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;

class OpenInstituteController extends Controller
{
    //

    public function getOpenInstitutes(){
        return Institute::orderBy('institute', 'asc')->get();
    }   
}
