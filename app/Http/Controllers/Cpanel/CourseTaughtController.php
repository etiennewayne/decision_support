<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CourseTaught;

class CourseTaughtController extends Controller
{
    //

    public function destroy($id){
        CourseTaught::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ], 200);
    }
}
