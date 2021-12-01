<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;

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
}