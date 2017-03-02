<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Group;
use App\User;
use App\GroupMember;
use App\Position;
use App\GroupRequest;

class GroupController extends Controller
{
	protected $namespace = null;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    $groups = Group::all();

	    $response = [
	        'groups' => $groups
	    ];

	    return response()->json($response);
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

        $user = User::where('facebook_id', $data['id'])->first();
        
        Group::firstOrCreate([
            'name' => $data['name'],
            'user_id' => $user->id
        ]);

        return response()->json(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMembers($id)
    {
        $member_ids = GroupMember::where('group_id', $id)
            ->pluck('user_id');

        $members = User::whereIn('id', $member_ids)->get();

        $response = [
            'members' => $members
        ];

        return response()->json($response);
    }

    /**
     * Handle requests
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addMember()
    {
        $validRequests = GroupRequest::where('status', 1)->get();

        foreach ($validRequests as $vReq) {
            GroupMember::firstOrCreate([
                'user_id' => $vReq->target_id,
                'group_id' => $vReq->group_id
            ]);

            // Delete request record(?)
        }

        return response()->json(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGroupPositions($id)
    {
        $group = Group::where('id', $id)->first();
        $groupMembers_id = GroupMember::where('group_id', $group->id)->pluck('user_id');
        $data = Position::whereIn('user_id', $groupMembers_id)->get();

        $response = [
            'data' => $data
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function makeRequest(Request $request, $id)
    {
        $data = $request->all();

        $group = Group::where('id', $data['group_id'])->first();
        $user = User::where('facebook_id', $data['user_id'])->first();
        
        $req = GroupRequest::firstOrCreate([
            'group_id' => $group->id,
            'target_id' => $id
        ]);
        $req->status = 0;
        $req->sender_id = $user->id;

        $req->save();


        return response()->json(true);
    }
}
