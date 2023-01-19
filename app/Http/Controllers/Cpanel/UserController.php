<?php

namespace App\Http\Controllers\Cpanel;

use App\Models\Office;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('cpanel.user.user'); //blade.php
    }

    public function getUsers(Request $req){
        $sort = explode('.', $req->sort_by);

        $users = User::where('lname', 'like', $req->lname . '%')
            ->orderBy($sort[0], $sort[1])
            ->paginate($req->perpage);

        return $users;
    }

    public function show($id){
        return User::find($id);
    }
    public function create(){
        $programs = Program::orderBy('program_code', 'asc')->get();

        return view('cpanel.user.user-create-edit')
            ->with('programs', $programs)
            ->with('data', '')
            ->with('id', 0);
    }
    public function store(Request $req){
        //this will create random unique QR code
        $qr_code = substr(md5(time() . $req->lname . $req->fname), -8);

        $validate = $req->validate([
            'username' => ['required', 'max:50', 'unique:users'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'sex' => ['required', 'string', 'max:20'],
            'email' => ['required', 'unique:users'],
            'institute_id' => Rule::requiredIf($req->role !== 'ADMINISTRATOR'),
            'password' => ['required', 'string', 'confirmed'],
            'role' => ['required', 'string'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'barangay' => ['required', 'string'],
        ]);

        User::create([
            'qr_ref' => $qr_code,
            'username' => $req->username,
            'password' => Hash::make($req->password),
            'lname' => strtoupper($req->lname),
            'fname' => strtoupper($req->fname),
            'mname' => strtoupper($req->mname),
            'suffix' => strtoupper($req->suffix),
            'sex' => $req->sex,
            'institute_id' => $req->institute_id,
            'email' => $req->email,
            'contact_no' => $req->contact_no,
            'role' => $req->role,
            'province' => $req->province,
            'city' => $req->city,
            'barangay' => $req->barangay,
            'street' => strtoupper($req->street)
        ]);

        return response()->json([
            'status' => 'saved'
        ]);
    }


    public function edit($id){
        $programs = Program::orderBy('program_code', 'asc')->get();
        $data = User::find($id);


        return view('cpanel.user.user-create-edit')
            ->with('programs', $programs)
            ->with('data', $data)
            ->with('id', $id);
    }


    public function update(Request $req, $id){

      
        $validate = $req->validate([
            'username' => ['required', 'max:50', 'unique:users,username,'.$id.',user_id'],
            'lname' => ['required', 'string', 'max:100'],
            'fname' => ['required', 'string', 'max:100'],
            'sex' => ['required', 'string', 'max:20'],
            'role' => ['required', 'string'],
            'institute_id' => Rule::requiredIf($req->role !== 'ADMINISTRATOR'),
            'email' => ['required', 'unique:users,email,'.$id.',user_id'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'barangay' => ['required', 'string'],
        ],[
            'institute_id.required' => 'Please select institute'
        ]);
        
        $data = User::find($id);
        $data->username = $req->username;
        $data->lname = strtoupper($req->lname);
        $data->fname = strtoupper($req->fname);
        $data->mname = strtoupper($req->mname);
        $data->sex = $req->sex;
        $data->institute_id = $req->institute_id;
        $data->contact_no = $req->contact_no;
        $data->email = $req->email;
        $data->role = $req->role;
        $data->province = $req->province;
        $data->city = $req->city;
        $data->barangay = $req->barangay;
        $data->street = strtoupper($req->street);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ]);
    }



    public function destroy($id){
        User::destroy($id);

        return response()->json([
            'status' => 'deleted'
        ]);
    }
}
