<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorTime;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DoctorTimeController extends Controller
{
    public function doctorTimes($id) : JsonResponse
    {
        $date = date("Y-m-d");

        $times = DoctorTime::where('doc_id', '=', $id)
                ->where('reserved', '=', 0)
                ->where('date', $date)
                ->get();

        return $this->success($times);
    }

    public function doctorTimeStore(Request $request, $id) : JsonResponse
    {
        $this->validate($request, [
            'dates' => 'required',
            'timesFrom'=> 'required',
            'timesTo' => 'required',
        ]);

        $dates = $request->dates;
        $timesFrom = $request->timesFrom;
        $timesTo = $request->timesTo;

        $doctor = Doctor::find($id);

        for($i = 0; $i < count($dates); $i++){
            $doctor->doctorTime()->create([
                'date' => $dates[$i],
                'time_from' => $timesFrom[$i],
                'time_to' => $timesTo[$i],
            ]);
        }

        return $this->success(null, 'The times recorded successfully');
    }
}
