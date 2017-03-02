<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\GroupRequest;

class RequestController extends Controller
{
    /**
     * Update an exisiting request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $item = GroupRequest::where('id', $id)->first();
        $item->status = $data['status'];
        $item->save();

        $response = [
        	'data' => $item
        ];

        return response()->json($response);
    }
}
