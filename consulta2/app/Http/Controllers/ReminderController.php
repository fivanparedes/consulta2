<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReminderController extends Controller
{
    public function confirm(Request $request) {
        $id = decrypt($request->id);
        $reminder = Reminder::find($id);
        $reminder->update([
            'answered' => now()
        ]);
        $reminder->calendarEvent->confirmed = true;
        $reminder->calendarEvent->save();
        $reminder->save();

        return view('external.confirm_assistance')->with(['reminder' => $reminder]);
    }

    public function willPay(Request $request) {
        $id = decrypt($request->id);
        $reminder = Reminder::find($id);
        $reminder->answered = now();
        $reminder->save();
        $data = array(
            'fullname' => $reminder->calendarEvent->professionalProfile->getFullName(),
            'email' => $reminder->calendarEvent->professionalProfile->profile->user->email
        );
        Mail::send('external.prof_willpay', [
            'treatment' => $reminder->calendarEvent->cite->treatment,
            'patient' => $reminder->calendarEvent->cite->treatment->patientProfile
        ], function ($message) use ($data) {
            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Paciente confirmó promesa de pago ');
            $message->from('sistema@consulta2.com', 'Consulta2');
        });

        return view('external.deleted');
    }

    public function cancelTreatment(Request $request) {
        $id = decrypt($request->id);
        $treatment = Treatment::find($id);
        $treatment->end = now();
        $treatment->save();
        return view('external.deleted');
    }

    public function mistake(Request $request) {
        $id = decrypt($request->id);
        $treatment = Treatment::find($id);
        $data = array(
            'fullname' => $treatment->medicalHistory->professionalProfile->getFullName(),
            'email' => $treatment->medicalHistory->professionalProfile->profile->user->email
        );
        Mail::send('external.prof_mistake', [
            'treatment' => $treatment,
            'patient' => $treatment->medicalHistory->patientProfile
        ], function ($message) use ($data) {
            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Paciente informó un error');
            $message->from('sistema@consulta2.com', 'Consulta2');
        });
        return view('external.deleted');
    }
}