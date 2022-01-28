<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\PatientProfile;
use App\Models\ProfessionalProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade as PDF;

class MedicalHistoryController extends Controller
{
    public function decryptField(Request $request)
    {
    }

    public function index()
    {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('patient-profile')) {
            $medicalHistories = $user->profile->patientProfile->medicalHistories;
            return view('medical_histories.home')->with([
                'medicalHistories' => $medicalHistories
            ]);
        } else {
            if ($user->isAbleTo('professional-profile')) {
                return view('medical_histories.index')->with([
                    'medicalHistories'
                ]);
            }
            return abort(404);
        }
    }

    public function create() {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('manage-histories')) {
            return view('medical_histories.create');
        } else {
            return abort(403);
        }
    }

    public function store(Request $request) {
        $user = User::find(auth()->user()->id);
        if (!$user->isAbleTo('manage-histories')) {
            return abort(403);
        } else {
            $medical_history = new MedicalHistory();
            $medical_history->indate = date_create($request->indate);
            $medical_history->visitreason = $request->visitreason;
            $medical_history->patient_profile_id = $request->patient_profile_id;
            $medical_history->professional_profile_id = $user->isAbleTo('professional-profile') ? $user->profile->professionalProfile->id : $request->professional_profile_id;
            $medical_history->institution_id = $user->isAbleTo('institution-profile') ? $user->institutionProfile->id : null;
            $medical_history->save();

            return redirect('/manage/patients');
        }
    }

    public function show(Request $request)
    {
        $id = decrypt($request->id);
        $medical_history = MedicalHistory::find($id);
        $user = $medical_history->patientProfile->profile->user;

        $psicological_history = Crypt::decryptString($medical_history->psicological_history);
        $visitreason = Crypt::decryptString($medical_history->visitreason);
        if ($user->id == auth()->user()->id) {
            return view('medical_histories.show')->with([
                'medical_history' => $medical_history,
                'psicological_history' => $psicological_history,
                'visitreason' => $visitreason
            ]);
        } else {
            if (auth()->user()->id == $medical_history->professionalProfile->profile->user->id) {
                return view('medical_histories.show')->with([
                    'medical_history' => $medical_history,
                    'psicological_history' => $psicological_history,
                    'visitreason' => $visitreason
                ]);
            } else {
                if (auth()->user()->id == $medical_history->institutionProfile->user->id) {
                    return view('medical_histories.show')->with([
                        'medical_history' => $medical_history,
                        'psicological_history' => $psicological_history,
                        'visitreason' => $visitreason
                    ]);
                }
            }
            return abort(404);
        }
    }

    public function createPDF(Request $request) {
        $id = decrypt($request->id);
        $medical_history = MedicalHistory::find($id);
        $user = $medical_history->patientProfile->profile->user;

        $psicological_history = Crypt::decryptString($medical_history->psicological_history);
        $visitreason = Crypt::decryptString($medical_history->visitreason);
        if ($user->id == auth()->user()->id) {

            $pdf = PDF::loadView('medical_histories.pdf',[
                'medical_history' => $medical_history,
                'psicological_history' => $psicological_history,
                'visitreason' => $visitreason
            ]);
            return $pdf->download('historia_medica_'.$user->name .'_' .$user->lastname.'.pdf');
        } else {
            if (auth()->user()->id == $medical_history->professionalProfile->profile->user->id) {
                $pdf = PDF::loadView('medical_histories.pdf', [
                    'medical_history' => $medical_history,
                    'psicological_history' => $psicological_history,
                    'visitreason' => $visitreason
                ]);
                return $pdf->download('historia_medica_' . $medical_history->id. '.pdf');
            } else {
                if (auth()->user()->id == $medical_history->institutionProfile->user->id) {
                    $pdf = PDF::loadView('medical_histories.pdf', [
                        'medical_history' => $medical_history,
                        'psicological_history' => $psicological_history,
                        'visitreason' => $visitreason
                    ]);
                    return $pdf->download('historia_medica_' . $medical_history->id . '.pdf');
                }
            }
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        return back()->withStatus('Se actualizó la historia médica.');
    }

    public function list(Request $request)
    {
        $patient = PatientProfile::find($request->id);
        $professional = User::find(Auth::user()->id);
        if ($professional->isAbleTo('receive-consults')) {
            return abort(403);
        }

        return view('professionals.histories')->with('history', $patient->medicalHistory);
    }

    public function toggleInstitutionPrivilege(Request $request)
    {
        if ($request->ajax()) {
            $medical_history = MedicalHistory::find($request->id);
            if ($medical_history->institution_id == null) {
                $medical_history->institution_id = $medical_history->professionalProfile->institution->id;
                $medical_history->save();
                return response()->json(['status' => 1]);
            } else {
                $medical_history->institution_id = null;
                $medical_history->save();
                return response()->json(['status' => 0]);
            }
        }
    }
}
