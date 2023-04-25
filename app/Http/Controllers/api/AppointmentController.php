<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatRequest;
use App\Models\Appointment;
use App\Models\Chat;
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
            'doc_id' => 'required|string|max:255',
            'patient_id' => 'required|string|max:255',
            'session_price' => 'required|string|max:255',
            'time_from' => 'required',
            'time_to' => 'required',
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
                'appointments.id',
                'appointments.status',
                'appointments.session_price',
                'appointments.date',
                'appointments.time_from',
                'appointments.time_to',
                'chats.id AS chat_id'
            ])
            ->join('doctors', 'doctors.id', '=', 'doc_id')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            ->join('chats', 'chats.appointment_id', '=', 'appointments.id')
            ->where('patient_id', '=', $id)
            ->orderBy('date')
            ->get();

        return $this->success($appointment);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function doctorAppointments($id) : JsonResponse
    {
        $appointments = Appointment::select([
            'appointments.id',
            'appointments.status',
            'appointments.date',
            'appointments.time_from',
            'appointments.time_to'
        ])->where('doc_id', $id)->get();

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
                'users.name',
                'appointments.id',
                'appointments.status',
                'appointments.date',
                'appointments.time_from',
                'appointments.time_to',
                'appointments.session_price'
            ])
            ->join('patients', 'patients.id', '=', 'patient_id')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->where('doc_id', '=', $id)
            ->where('status', 'pending')
            ->get();

        return $this->success($appointments);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function appointmentsAction(Request $request)
    {
        $this->validate($request, [
           'appointment_id' => 'required',
           'action' => 'required'
        ]);

        $appointment = Appointment::find($request->appointment_id);

        if($request->action){
            $appointment->update([
                'status' => 'accepted'
            ]);

            $patient_user_id = DB::table('patients')
                ->select('users.id')
                ->join('users', 'users.id', '=', 'user_id')
                ->where('patients.id', '=', $appointment['patient_id'])
                ->first();

            $appointment->patient_id = $patient_user_id->id;

            $request['user_id'] = $patient_user_id->id;

            $storeChat = new ChatController();

            $data = $storeChat->store($request);
        }
        else{
            $appointment->update([
                'status' => 'canceled'
            ]);
        }

        return $this->success($appointment);

    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function todayAppointments($id) : JsonResponse
    {
        $date = date("Y-m-d");
        $appointments = DB::table('appointments')
            ->select([
                'appointments.id',
                'appointments.status',
                'appointments.date',
                'appointments.time_from',
                'appointments.time_to',
                'appointments.session_price',
                'users.name',
                'chats.id AS chat_id'
            ])
            ->join('patients', 'patients.id', '=', 'patient_id')
            ->join('users', 'users.id', '=', 'patients.user_id')
            ->join('chats', 'chats.appointment_id', '=', 'appointments.id')
            ->where('doc_id', '=', $id)
            ->where('status', 'accepted')
            ->where('date', '=', $date)
            ->orderBy('time_from')
            ->get();


        return $this->success($appointments);
    }


    public function labtest($id)
    {
        $appointment = Appointment::find($id);

        $patient_user_id = DB::table('patients')
            ->select('users.id')
            ->join('users', 'users.id', '=', 'user_id')
            ->where('patients.id', '=', $appointment['patient_id'])
            ->first();

        return $this->success($patient_user_id->id);
    }
}
