<?php

namespace App\Http\Controllers\api;

use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : JsonResponse
    {
        $sessions = Session::all();

        return $this->success($sessions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'status' => 'required',
            'type' => 'required',
            'time' => 'required',
            'report' => 'required',
        ]);

        $session = Session::create($request->all());

        return $this->success($session, 'Session created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) : JsonResponse
    {
        $session = Session::find($id);
        
        if(!$session)
            return [];
        
        return $this->success($session);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) : JsonResponse
    {
        $session = Session::find($id);

        if($session){
            $request->validate([
                'status' => 'required',
                'type' => 'required',
                'time' => 'required',
                'report' => 'required',
            ]);
            $session->update($request->all());
            return $this->success($session, 'Session Updated successfully');
        }

        return $this->error('The session with this ID not found');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) : JsonResponse
    {
        $session = Session::find($id);
        if($session)
            return $this->success(null, 'Session deleted successfully');
        
        return $this->error('The session with this ID not found');
    }
}
