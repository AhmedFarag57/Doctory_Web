<?php

namespace App\Helpers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\Patient;

class Helper
{
    public static function send_appointment_notification($appointmentId, $token) : bool
    {
        $title = '';
        $description = '';

        try{
            $appointment = Appointment::where('id', $appointmentId)->first();

            $status = $appointment->status;

            if($status == 'pending') {
                $title = 'Appointment Recorded';
                $description = 'Your appointment with id '
                    . $appointment->id
                    . ' has been recorded successfully at '
                    . $appointment->date
                    . ' from '
                    . $appointment->time_from
                    . ' to '
                    . $appointment->time_to
                    . ' .';
            }
            else if($status == 'canceled') {
                $title = 'Appointment Canceled';
                $description = 'Your appointment with id '
                    . $appointment->id
                    . ' at '
                    . $appointment->date
                    . ' from '
                    . $appointment->time_from
                    . ' to '
                    . $appointment->time_to
                    . ' has been canceled.';
            }
            else if($status == 'completed') {
                $title = 'Appointment Completed';
                $description = "Appointment has been completed successfully";
            }
            else if($status == 'accepted') {
                $title = 'Appointment Accepted';
                $description = 'Your appointment with id '
                    . $appointment->id
                    . ' at '
                    . $appointment->date
                    . ' from '
                    . $appointment->time_from
                    . ' to '
                    . $appointment->time_to
                    . ' has been accepted.';
            }

            $data = [
                'title' => $title,
                'description' => $description,
            ];

            // Send the notification
            self::send_push_notification($token, $data);

            $patientId = $appointment->patient_id;
            $patient = Patient::where('id', $patientId)->first();

            $notification = Notification::create([
                'user_id' => $patient->user->id,
                'title' => $data['title'],
                'description' => $data['description'],
            ]);

            return true;

        }catch (\Exception $e){
            return false;
        }
    }

    public static function send_appointment_request_notification($appointmentId, $token)
    {
        try{
            $appointment = Appointment::where('id', $appointmentId)->first();

            $data = [
                'title' => "Appointment Request",
                'description' => "You have requested appointment at " . $appointment->date . " from " . $appointment->time_from . " to " . $appointment->time_to . " .",
            ];

            // Send the notification
            self::send_push_notification($token, $data);

            $doctorId = $appointment->doctor_id;
            $doctor = Doctor::where('id', $doctorId)->first();

            $notification = Notification::create([
                'user_id' => $doctor->user->id,
                'title' => $data['title'],
                'description' => $data['description'],
            ]);

            return true;

        }catch (\Exception $e){
            return false;
        }
    }

    /**
     * @param $token
     * @param $data
     * @return bool
     */
    public static function send_push_notification($token, $data) : bool
    {
        $key = "AAAAGmUmJBc:APA91bF2CzbVTVa1kK2wUiCySDvwPEnEfOPVfQQ0e8sBEVnGqUU3-df8VA9ydVctHC2PdeyepJP0DLIMdyfgPmQ2MtpnL5Flh25cmuG0Wg9raJBXu6QXyak4SD2-CvlXqneY3Na6q7q7";
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array("authorization: key=". $key. "", "Content-Type: application/json");

        $postData = '{
            "to" : "'. $token . '",
            "mutable_content" : true,
            "data": {
                "title": "'. $data['title'] . '",
                "body": "'. $data['description'] . '",
            },
            "notification": {
                "title": "'. $data['title'] . '",
                "body": "'. $data['description'] . '",
                "android_channel_id": "serenity",
            }
        }';

        $ch = curl_init();
        $timeout = 120;

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}
