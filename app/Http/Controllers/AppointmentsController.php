<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::orderBy('date', 'desc')->paginate(10);

        return view('backend.admin.appointments.index')->with('appointments', $appointments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctors = Doctor::where('accepted', 1)->orderBy('id', 'asc')->get();
        if ($doctors->isEmpty()) {
            $title = 'Error';
            $message = "No Doctor Has Assigned Yet. Create one ?";
            $route = 'doctors.create';
            $action = 'Create Doctor';
            return view('errors.message')->with([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'action' => $action
            ]);
        }

        $patients = Patient::all();
        if ($patients->isEmpty()) {
            $title = 'Error';
            $message = "There is no patient in the system. Create one ?";
            $route = 'patients.create';
            $action = 'Create Patient';
            return view('errors.message')->with([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'action' => $action
            ]);
        }

        return view('backend.admin.appointments.create')->with([
            'doctors' => $doctors,
            'patients' => $patients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'doctor_id' => 'required',
            'patient_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'session_price' => 'nullable|numeric'
        ]);

        if($request->input('session_price') == null){
            $session_price = DB::table('doctors')->where('id', $request->input('doctor_id'))->value('session_price');
        }
        else {
            $session_price = $request->input('session_price');
        }

        Appointment::create([
            'doc_id' => $request->input('doctor_id'),
            'patient_id' => $request->input('patient_id'),
            'session_price' => $session_price,
            'time' => $request->input('time'),
            'date' => $request->input('date'),
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);

        return view('backend.admin.appointments.show')->with('appointment', $appointment);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $appointment = Appointment::find($id);

        return view('backend.admin.appointments.edit')->with([
            'appointment' => $appointment,
            'status' => [
                'pending',
                'canceled',
                'completed',
                'accepted'
            ]
        ]);
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
        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'time' => 'required',
            'session_price' => 'nullable|numeric'
        ]);

        $appointment = Appointment::find($id);

        if($request->input('session_price') == null){
            $session_price = DB::table('doctors')->where('id', $appointment->doctor->id)->value('session_price');
        }
        else {
            $session_price = $request->input('session_price');
        }

        $appointment->update([
            'session_price' => $session_price,
            'time' => $request->input('time'),
            'date' => $request->input('date'),
            'status' => $request->input('status')
        ]);

        return redirect()->route('appointments.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->delete();
        }

        return redirect()->route('appointments.index');
    }
}
