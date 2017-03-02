<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Position;
use App\UserPosition;
use App\GroupRequest;
use App\Group;

class UserController extends Controller
{

    protected $namespace = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        $response = [
            'users' => $users
        ];

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        $user = User::firstOrCreate([
            'facebook_id' => $data['id']
        ]);

        $user->name = $data['name'];
        $user->image = $data['picture']['data']['url'];
        $user->save();

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('facebook_id', $id)->get();

        $response = [
            'user' => $user
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showRequests($id)
    {
        $all_requests = [];

        $user = User::where('facebook_id', $id)->first();

        $requests = GroupRequest::where('target_id', $user->id)->where('status', 0)->get();

        foreach ($requests as $request) {
            $request->sender = User::where('id', $request->sender_id)->first();
            $request->group = Group::where('id', $request->group_id)->first();

            $all_requests[] = $request;
        }
        
        $response = [
            'data' => $all_requests,
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPosition($id)
    {
        $position = Position::where('user_id', $id)->first();

        $response = [
            'position' => $position
        ];

        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePosition(Request $request, $fid)
    {
        $data = $request->all();

        $user = User::where('facebook_id', $fid)->first();

        $position = Position::firstOrCreate([
            'user_id' => $user->id,
        ]);
        $position->lat = $data['lat'];
        $position->lng = $data['lng'];
        $position->save();

        return response()->json($position);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('id', $id)->delete();

        return 'true';
    }
}
