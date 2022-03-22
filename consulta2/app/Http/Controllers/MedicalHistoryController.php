<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\MedicalHistory;
use App\Models\PatientProfile;
use App\Models\ProfessionalProfile;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault;
use Illuminate\Support\Str;
use Throwable;

class MedicalHistoryController extends Controller
{
    public function decryptField(Request $request)
    {
    }

    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('patient-profile')) {
            $medicalHistories = $user->profile->patientProfile->medicalHistories;
            $patientProfile = Auth::user()->profile->patientProfile;
            $events = $patientProfile->calendarEvents->where('active', true)->toQuery();
            if ($request->has('filter2') && $request->filter2 != "") {
                $professionals = new Collection();
                $users = User::where('name', 'like', '%'.$request->filter2.'%')->orWhere('lastname', 'like', '%' . $request->filter2 . '%')->get();
                foreach ($users as $user) {
                    if (isset($user->profile->professionalProfile)) {
                        $professionals->push($user->profile->professionalProfile);
                    }
                }
                $events = $events->whereIn('professional_profile_id', $professionals->toArray(['id']));
            }
            if ($request->has('filter3') && $request->filter3 != "") {
                $from = date_create($request->filter3);
                $events = $events->where('start', '>=', $from);
            }
            if ($request->has('filter4') && $request->filter4 != "") {
                $to = date_create($request->filter4);
                $events = $events->where('start', '<=', $to);
            }
            if ($request->has('filter5') && $request->filter5 != "") {
                $specialties = Specialty::where('displayname', 'like', '%'.$request->filter5.'%')->get();
                $professionals = ProfessionalProfile::whereIn('specialty_id', $specialties->toArray(['id']))->get();
                $events = $events->whereIn('professional_profile_id', $professionals->toArray(['id']));
            }
            $events = $events->sortable()->paginate(10);
            //dd(Auth::user()->profile->patientProfile);
            return view('medical_histories.home')->with([
                'medicalHistories' => $medicalHistories,
                'patientProfile' => $patientProfile,
                'events' => $events,
                'filter1' => $request->has('filter1') ? $request->filter1 : '',
                'filter2' => $request->has('filter2') ? $request->filter2 : '',
                'filter3' => $request->has('filter3') ? $request->filter3 : '',
                'filter4' => $request->has('filter4') ? $request->filter4 : '',
                'filter5' => $request->has('filter5') ? $request->filter5 : '',
            ]);
        } else {
            
                $medicalHistories = PatientProfile::find($request->patientid)->medicalHistories;
                $patientProfile = PatientProfile::find($request->patientid);
                return view('medical_histories.home')->with([
                    'medicalHistories' => $medicalHistories,
                    'patientProfile' => $patientProfile,
                    'events' => $patientProfile->calendarEvents->where('active', true)->toQuery()->orderByDesc('start')->sortable()->paginate(10),
                'filter1' => $request->has('filter1') ? $request->filter1 : '',
                'filter2' => $request->has('filter2') ? $request->filter2 : '',
                'filter3' => $request->has('filter3') ? $request->filter3 : '',
                'filter4' => $request->has('filter4') ? $request->filter4 : '',
                'filter5' => $request->has('filter5') ? $request->filter5 : '',
                ]);
            
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
        $request->validate([
            'indate' => 'required|date:Y-m-d',
            'psicological_history' => 'required|string|filled|max:300',
            'visitreason' => 'required|string|filled|max:300',
            'clinical_history' => 'required|string|filled|max:300',
            'diagnosis' => 'required|string|filled|max:100',
        ]);
        $user = User::find(auth()->user()->id);
        if (!$user->isAbleTo('manage-histories')) {
            return abort(403);
        } else {
            $medical_history = new MedicalHistory();
            $medical_history->indate = date_create($request->indate);
            $medical_history->psicological_history = encrypt($request->psicological_history);
            $medical_history->visitreason = encrypt($request->visitreason);
            $medical_history->clinical_history = encrypt($request->clinical_history);
            $medical_history->diagnosis = encrypt($request->diagnosis);
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

        $psicological_history = $medical_history->psicological_history != '** Sin datos **' ? decrypt($medical_history->psicological_history) : $medical_history->psicological_history;
        $visitreason = $medical_history->visitreason != "** Sin datos **" ? decrypt($medical_history->visitreason) : $medical_history->visitreason;
        $diagnosis = $medical_history->diagnosis != '** Sin datos **' ? decrypt($medical_history->diagnosis) : $medical_history->diagnosis;
        $clinical_history = $medical_history->clinical_history != "** Sin datos **" ? decrypt($medical_history->clinical_history) : $medical_history->clinical_history;
        if ($user->id == auth()->user()->id) {
            return view('medical_histories.show')->with([
                'medical_history' => $medical_history,
                'psicological_history' => $psicological_history,
                'visitreason' => $visitreason,
                'diagnosis' => $diagnosis,
                'clinical_history' => $clinical_history
            ]);
        } else {
            if (auth()->user()->id == $medical_history->professionalProfile->profile->user->id) {
                return view('medical_histories.show')->with([
                    'medical_history' => $medical_history,
                    'psicological_history' => $psicological_history,
                    'visitreason' => $visitreason,
                    'diagnosis' => $diagnosis,
                    'clinical_history' => $clinical_history
                ]);
            } else {
                if (auth()->user()->id == $medical_history->institutionProfile->user->id) {
                    return view('medical_histories.show')->with([
                        'medical_history' => $medical_history,
                        'psicological_history' => $psicological_history,
                        'visitreason' => $visitreason,
                        'diagnosis' => $diagnosis,
                        'clinical_history' => $clinical_history
                    ]);
                }
            }
            return abort(404);
        }
    }

    public function edit(Request $request) {
        $id = decrypt($request->id);
        $medical_history = MedicalHistory::find($id);
        $user = $medical_history->patientProfile->profile->user;

        $psicological_history = $medical_history->psicological_history != '** Sin datos **' ? decrypt($medical_history->psicological_history) : $medical_history->psicological_history;
        $visitreason = $medical_history->visitreason != "** Sin datos **" ? decrypt($medical_history->visitreason) : $medical_history->visitreason;
        $diagnosis = $medical_history->diagnosis != '** Sin datos **' ? decrypt($medical_history->diagnosis) : $medical_history->diagnosis;
        $clinical_history = $medical_history->clinical_history != "** Sin datos **" ? decrypt($medical_history->clinical_history) : $medical_history->clinical_history;
        if ($user->id == auth()->user()->id) {
            return view('medical_histories.edit')->with([
                'medical_history' => $medical_history,
                'psicological_history' => $psicological_history,
                'visitreason' => $visitreason,
                'diagnosis' => $diagnosis,
                'clinical_history' => $clinical_history
            ]);
        } else {
            if (auth()->user()->id == $medical_history->professionalProfile->profile->user->id) {
                return view('medical_histories.edit')->with([
                    'medical_history' => $medical_history,
                    'psicological_history' => $psicological_history,
                    'visitreason' => $visitreason,
                    'diagnosis' => $diagnosis,
                    'clinical_history' => $clinical_history
                ]);
            } else {
                if (auth()->user()->id == $medical_history->institutionProfile->user->id) {
                    return view('medical_histories.edit')->with([
                        'medical_history' => $medical_history,
                        'psicological_history' => $psicological_history,
                        'visitreason' => $visitreason,
                        'diagnosis' => $diagnosis,
                        'clinical_history' => $clinical_history
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

        $psicological_history = $medical_history->psicological_history != '** Sin datos **' ? decrypt($medical_history->psicological_history) : $medical_history->psicological_history;
        $visitreason = $medical_history->visitreason != "** Sin datos **" ? decrypt($medical_history->visitreason) : $medical_history->visitreason;
        $diagnosis = $medical_history->diagnosis != '** Sin datos **' ? decrypt($medical_history->diagnosis) : $medical_history->diagnosis;
        $clinical_history = $medical_history->clinical_history != "** Sin datos **" ? decrypt($medical_history->clinical_history) : $medical_history->clinical_history;
        
        if ($user->id == auth()->user()->id) {
            $companyLogo = DB::table('settings')->where('name', 'company-logo')->first(['value']);
            $pdf = PDF::loadView('medical_histories.pdf',[
                'medical_history' => $medical_history,
                'psicological_history' => $psicological_history,
                'visitreason' => $visitreason,
                'diagnosis' => $diagnosis,
                'clinical_history' => $clinical_history,
                'companyLogo' => $companyLogo->value
            ]);
            return $pdf->download('historia_medica_'.$user->name .'_' .$user->lastname.'.pdf');
        } else {
            if (auth()->user()->id == $medical_history->professionalProfile->profile->user->id) {
                $companyLogo = DB::table('settings')->where('name', 'company-logo')->first(['value']);
                $pdf = PDF::loadView('medical_histories.pdf', [
                    'medical_history' => $medical_history,
                    'psicological_history' => $psicological_history,
                    'visitreason' => $visitreason,
                    'diagnosis' => $diagnosis,
                    'clinical_history' => $clinical_history,
                    'companyLogo' => $companyLogo
                ]);
                return $pdf->download('historia_medica_' . $medical_history->id. '.pdf');
            } else {
                if (auth()->user()->id == $medical_history->institutionProfile->user->id) {
                    $companyLogo = DB::table('settings')->where('name', 'company-logo')->first(['value']);
                    $pdf = PDF::loadView('medical_histories.pdf', [
                        'medical_history' => $medical_history,
                        'psicological_history' => $psicological_history,
                        'visitreason' => $visitreason,
                        'diagnosis' => $diagnosis,
                        'clinical_history' => $clinical_history,
                        'companyLogo' => $companyLogo
                    ]);
                    return $pdf->download('historia_medica_' . $medical_history->id . '.pdf');
                }
            }
            return abort(404);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'indate' => 'required|date:Y-m-d',
            'psicological_history' => 'required|string|filled|max:300',
            'visitreason' => 'required|string|filled|max:300',
            'clinical_history' => 'required|string|filled|max:300',
            'diagnosis' => 'required|string|filled|max:100',
        ]);
        $user = User::find(auth()->user()->id);
        if (!$user->isAbleTo('manage-histories')) {
            return abort(403);
        } else {
            $medical_history = MedicalHistory::find($request->id);
            $medical_history->indate = date_create($request->indate);
            $medical_history->psicological_history = encrypt($request->psicological_history);
            $medical_history->visitreason = encrypt($request->visitreason);
            $medical_history->clinical_history = encrypt($request->clinical_history);
            $medical_history->diagnosis = encrypt($request->diagnosis);
            $medical_history->save();

            return redirect('/medical_history/'.encrypt($medical_history->id));
        }
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

    public function attachDocument(Request $request) {
        $request->validate([
            'document' => 'required|mimes:pdf,doc,docx,odt|max:10240'
        ]);

        if ($request->hasFile('document') && $request->file('document')->isValid()) {
            try {
                $path = Storage::putFile('files/histories/' . decrypt($request->medical_history_id), $request->file('document'));
                FileVault::encrypt($path);
                $document = new Document();
                $document->name = $request->file('document')->getClientOriginalName();
                $document->path = $path;
                $document->medical_history_id = decrypt($request->medical_history_id);
                $document->save();
            } catch (\Throwable $th) {
                return back()->withErrors('error', $th->getMessage());
            }
        }

        return back();
    }

    public function download($id)
    {
        try {
            $document = Document::find($id);
            if (!Storage::has($document->path . '.enc')) {
                abort(404);
            }
            $arr = explode('/', $document->path);
            return response()->streamDownload(function () use ($document) {
                FileVault::streamDecrypt($document->path.'.enc');
            }, $document->name);
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
        
    }

    public function detachDocument($id) {
        $document = Document::find($id);
        Document::destroy($id);
        Storage::delete($document->path);
        return back();
    }

    public function locatePatient(Request $request) {
        if ($request->ajax()) {
            $user = User::where('dni', $request->dni)->first();
            if ($user != null) {
                return response()->json($user->profile->patientProfile);
            } else {
                return response()->json(['status' => 'error']);
            }
        }
    }
}