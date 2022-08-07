<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CpanelDashboardController extends Controller
{
    //

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        return view('cpanel.dashboard');
    }

}
