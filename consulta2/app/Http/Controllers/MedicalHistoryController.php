<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\PatientProfile;
use App\Models\ProfessionalProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MedicalHistoryController extends Controller
{
    public function decryptField(Request $request)
    {
        if ($request->ajax()) {
            $history = MedicalHistory::find($request->hid);
            switch ($request->touched) {
                case '0':
                    return response()->json(['status' => 'success',
                    'text' => (string)decrypt($history->indate)]);
                    break;
                case '1':
                    return response()->json([
                        'status' => 'success',
                    'text' => (string)decrypt($history->psicological_history)]);
                    break;
                default:
                    echo '<p>Error.</p>';
                    break;
            }
        }
    }

    public function show(Request $request)
    {
        $patient = PatientProfile::find($request->id);
        $professional = User::find(Auth::user()->id);
        if (!$professional->isAbleTo('receive-consults')) {
            return abort(403);
        }

        return view('professionals.histories')->with('history', $patient->medicalHistory);
    }

    public function update(Request $request)
    {
        return back()->withStatus('Se actualizó la historia médica.');
    }
}