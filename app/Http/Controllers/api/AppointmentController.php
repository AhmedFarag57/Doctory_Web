<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isNull;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $appointment = Appointment::all();

        return $this->success($appointment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'doc_id' => 'required',
            'patient_id' => 'required',
            'status' => 'required',
            'session_price' => 'required',
            'time' => 'required',
            'date' => 'required'
        ]);

        $appointment = Appointment::create($request->all());

        return $this->success($appointment, 'Appointment created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id) : JsonResponse
    {
        $appointment = Appointment::find($id);

        if(isNull($appointment)){
            $this->error('Appointment with this ID does not exist');
        }

        return $this->success($appointment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id) : JsonResponse
    {
        $appointment = Appointment::find($id);

        if(isNull($appointment))
            return $this->error('Appointment with this ID does not exist');

        $request->validate([
            'doc_id' => 'required',
            'patient_id' => 'required',
            'status' => 'required',
            'session_price' => 'required',
            'time' => 'required',
            'date' => 'required'
        ]);

        $appointment->update($request->all());

        return $this->success($appointment, 'Appointment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id) : JsonResponse
    {
        $appointment = Appointment::find($id);

        if(isNull($appointment))
            return $this->error('Appointment with this ID does not exist');

        $appointment->destroy();

        return $this->success(null, 'Appointment deleted successfully');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function patientAppointment($id) : JsonResponse
    {
        $appointment = DB::table('appointments')
            ->select([
                'users.name',
                'appointments.status',
                'appointments.date',
                'appointments.time'
            ])
            ->join('doctors', 'doctors.id', '=', 'doc_id')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->where('patient_id', '=', $id)
            ->get();

        return $this->success($appointment);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function doctorAppointments($id) : JsonResponse
    {
        $appointments = DB::table('appointments')
            ->select([
                'appointments.id',
                'appointments.status',
                'appointments.date',
                'appointments.time'
            ])
            ->join('doctors', 'doctors.id', '=', 'doc_id')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->where('doc_id', '=', $id)
            ->get();


        return $this->success($appointments);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function appointmentsRequest($id) : JsonResponse
    {
        $appointments = DB::table('appointments')
            ->select([
                'appointments.id',
                'appointments.status',
                'appointments.date',
                'appointments.time'
            ])
            ->join('doctors', 'doctors.id', '=', 'doc_id')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->where('doc_id', '=', $id)
            ->where('status', 'pending')
            ->get();

        return $this->success($appointments);
    }
}
