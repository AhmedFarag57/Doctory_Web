<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $points = DB::table('points_request')
            ->select([
                'points_request.id',
                'points_request.points',
                'points_request.isAdd',
                'users.name'
            ])
            ->join('users', 'points_request.user_id', '=', 'users.id')
            ->paginate(10);

        return view('backend.admin.points.index')->with('points', $points);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $point = DB::table('points_request')
            ->select([
                'points_request.id',
                'points_request.points',
                'points_request.isAdd',
                'users.name'
            ])
            ->join('users', 'users.id', '=', 'user_id')
            ->where('points_request.id', '=', $id)
            ->first();

        return view('backend.admin.points.show')->with('point', $point);
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function acceptRequest($id)
    {
        $point = DB::table('points_request')->where('id', '=', $id)->first();

        $patient = Patient::where('user_id', '=', $point->user_id)->first();

        $wallet = floatval($patient->wallet);
        $points = floatval($point->points);
        $newAmount = $wallet + $points;

        $patient->update([
            'wallet' => $newAmount,
        ]);

        $point = DB::table('points_request')->where('id', '=', $id)->update([
            'isAdd' => true,
        ]);

        return redirect()->route('patients.show', $patient->id);
    }

    /**
     * API function
     */
    public function apiPointsRequest(Request $request)
    {
        $this->validate($request, [
            'points' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $dateTime = Carbon::now()->toDateTimeString();

        try {
            $pointsId = DB::table('points_request')->insertGetId([
                'user_id' => $user->id,
                'points' => $request->points,
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ]);
        } catch (\Exception $exception){
            return $this->error('Error in insert point request. plz try again');
        }

        return $this->success('Points request recorded successfully.');
    }
}
