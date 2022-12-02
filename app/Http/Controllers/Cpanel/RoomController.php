<?php

namespace App\Http\Controllers\Cpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Room;


class RoomController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('cpanel.rooms.rooms'); //blade.php
    }


    public function show($id){
        return Room::where('room_id', $id)->orderBy('room', 'asc')->first();
    }


    public function getRooms(Request $req){
        $sort = explode('.', $req->sort_by);

        $data = Room::where('room', 'like', $req->room . '%')
            ->orderBy('room', 'asc')
            ->paginate($req->perpage);
        return $data;
    }

    public function store(Request $req){

        $req->validate([
            'room' => ['required', 'unique:rooms'],
            'room_desc' => ['required']
        ]);

        Room::create([
            'room' => strtoupper($req->room),
            'room_desc' => strtoupper($req->room_desc),

        ]);

        return response()->json([
            'status' => 'saved'
        ], 200);
    }


    public function update(Request $req, $id){
        $req->validate([
            'room' => ['required', 'unique:rooms,room,'.$id.',room_id'],
            'room_desc' => ['required']
        ]);

        $data = Room::find($id);
        $data->room = strtoupper($req->room);
        $data->room_desc = strtoupper($req->room_desc);
        $data->max = strtoupper($req->max);
        $data->save();

        return response()->json([
            'status' => 'updated'
        ], 200);
    }

    public function destroy($id){
        Room::destroy($id);
        return response()->json([
            'status' => 'deleted'
        ], 200);
    }


}
