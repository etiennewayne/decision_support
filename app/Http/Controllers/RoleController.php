<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    //

    public function getAllRoles(){
        return Role::orderBy('role', 'asc')->get();
    }
}
