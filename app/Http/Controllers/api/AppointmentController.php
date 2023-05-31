<?php

namespace App\Http\Controllers\api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorTime;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;
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
            'time_id' => 'required|string|max:255',
            'time_from' => 'required',
            'time_to' => 'required',
            'date' => 'required'
        ]);

        // check if the patient have enough point or not
        $patient = Patient::where('id', $request->patient_id)->first();

        $patient_wallet = floatval($patient->wallet);
        $session_price = floatval($request->session_price);

        if($patient_wallet >= $session_price){
            $new_amount = $patient_wallet - $session_price;
            $patient->update([
                'wallet' => $new_amount,
            ]);
        }
        else {
            return $this->error('You don\'t have enough points to book an appointment.');
        }

        $appointment = Appointment::create($request->all());

        $doctorTime = DoctorTime::find($request->time_id);
        $doctorTime->update([
           'reserved' => true,
        ]);

        $doctor = Doctor::where('id', $appointment->doc_id)->first();

        Helper::send_appointment_request_notification($appointment->id, $doctor->user->firebase_token);

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
            'time_from' => 'required',
            'time_to' => 'required',
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
                //'chats.id AS chat_id'
            ])
            ->join('doctors', 'doctors.id', '=', 'doc_id')
            ->join('users', 'users.id', '=', 'doctors.user_id')
            //->join('chats', 'chats.appointment_id', '=', 'appointments.id')
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
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function appointmentsAction(Request $request): JsonResponse
    {
        $this->validate($request, [
           'appointment_id' => 'required',
           'action' => 'required'
        ]);

        $appointment = Appointment::find($request->appointment_id);

        $patient = Patient::where('id', $appointment->patient_id)->first();

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

            // Add the point to Doctor
            $doctor = Doctor::where('id', $appointment->doc_id)->first();

            $doctor_wallet = floatval($doctor->wallet);
            $session_price = floatval($appointment->session_price);

            $new_amount = $doctor_wallet + $session_price;

            $doctor->update([
                'wallet' => $new_amount,
            ]);

            Helper::send_appointment_notification($appointment->id, $patient->user->firebase_token);

            return $this->success($appointment);
        }
        else{
            $appointment->update([
                'status' => 'canceled'
            ]);

            // return the point to Patient
            $patient_wallet = floatval($patient->wallet);
            $session_price = floatval($appointment->session_price);

            $new_amount = $patient_wallet + $session_price;

            $patient->update([
                'wallet' => $new_amount,
            ]);

            Helper::send_appointment_notification($appointment->id, $patient->user->firebase_token);

            return $this->success($appointment);
        }
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

    /**
     * Count the number of appointments for a doctor
     *
     * @param int $id
     * @return JsonResponse
     */
    public function appointmentsCount($id) : JsonResponse
    {
        $count = Appointment::where('doc_id', $id)->count();
        return $this->success($count);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function appointmentChatId($id) : JsonResponse
    {
        $appointment = Appointment::where('id', $id)->first();

        if(!($appointment != null)){
            return $this->error('No appointment by this Id');
        }

        if(!($appointment->status == 'accepted')){
            return $this->error('This appointment does not accepted yet');
        }

        $chatId = $appointment->chat->id;
        return $this->success($chatId);
    }

    public function labtest($id)
    {
        // ..
    }
}
