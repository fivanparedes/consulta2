<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Cite;
use App\Models\Document;
use App\Models\MedicalHistory;
use App\Models\Practice;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault;

class CiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $cites = CalendarEvent::where('active', true);
        if ($user->isAbleTo('professional-profile')) {
            $cites = $cites->where('professional_profile_id', Auth::user()->profile->professionalProfile->id);
        } elseif ($user->isAbleTo('institution-profile')) {
            $professionals = Auth::user()->institutionProfile->professionalProfiles->toArray(['id']);
            $cites = $cites->whereIn('professional_profile_id', $professionals);
        } elseif ($user->isAbleTo('admin-profile')) {
            $cites = $cites->whereHas('professionalProfile', function($q) {
                return $q->where('status', '<>', 0);
            });
        }
        
        if ($request->has('filter1')) {
            $today = date_create('now');
            switch ($request->filter1) {
                case 'future':
                    $cites = $cites->where('start', ">=", $today);
                    break;
                case 'past':
                    $cites = $cites->where('start', "<=", $today);
                    break;
            }
            
        } else {
            $today = date_create('now');
            $cites = $cites->where('start', ">=", $today);
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            //$name = $this->sec->clean($request->filter2);
            $cites = $cites->where('title', 'like', '%' . $request->filter2 . '%');
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $from = date_create($request->filter3);
            $cites = $cites->where('start', '>=', $from);
        }
        if ($request->has('filter4') && $request->filter4 != "") {
            $to = date_create($request->filter4);
            $cites = $cites->where('start', '<=', $to);
        }
        if ($request->has('filter5') && $request->filter5 != "") {
            $cites = $cites->where('isVirtual', $request->filter5);
        }
        if ($request->has('filter6') && $request->filter6 != "") {
            $cites = $cites->where('assisted', $request->filter6);
        }
        $cites = $cites->sortable()->paginate(10);
        //dd($cites);
        return view('pages.cites')->with([
            'cites' => $cites,
            //'professional' => Auth::user()->profile->professionalProfile,
            'filter1' => $request->has('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->has('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->has('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->has('filter4') != "" ? $request->input('filter4') : null,
            'filter5' => $request->has('filter5') != "" ? $request->input('filter5') : null,
            'filter6' => $request->has('filter6') != "" ? $request->input('filter6') : null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function createPDF(Request $request) {
        $user = User::find(auth()->user()->id);
        $cites = CalendarEvent::all()->toQuery();
        if ($user->isAbleTo('professional-profile')) {
            $cites = CalendarEvent::where('active', true)->where('professional_profile_id', Auth::user()->profile->professionalProfile->id);
        } elseif ($user->isAbleTo('institution-profile')) {
            $professionals = Auth::user()->institutionProfile->professionalProfiles->get(['id']);
            $cites = CalendarEvent::where('active', true)->whereIn('professional_profile_id', $professionals);
        } elseif ($user->isAbleTo('admin-profile')) {
            $cites = CalendarEvent::where('active', true)->whereHas('professionalProfile', function ($q) {
                return $q->where('status', '<>', 0);
            });
        }

        if ($request->has('filter1')) {
            $today = date_create('now');
            switch ($request->filter1) {
                case 'future':
                    $cites = $cites->where('start', ">=", $today);
                    break;
                case 'past':
                    $cites = $cites->where('start', "<=", $today);
                    break;
            }
        } else {
            $today = date_create('now');
            $cites = $cites->where('start', ">=", $today);
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            //$name = $this->sec->clean($request->filter2);
            $cites = $cites->where('title', 'like', '%' . $request->filter2 . '%');
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $from = date_create($request->filter3);
            $cites = $cites->where('start', '>=', $from);
        }
        if ($request->has('filter4') && $request->filter4 != "") {
            $to = date_create($request->filter4);
            $cites = $cites->where('start', '<=', $to);
        }
        if ($request->has('filter5') && $request->filter5 != "") {
            $cites = $cites->where('isVirtual', $request->filter5);
        }
        if ($request->has('filter6') && $request->filter6 != "") {
            $cites = $cites->where('assisted', $request->filter6);
        }
        $companyLogo = DB::table('settings')->where('name', 'company-logo')->first(['value']);
        $cites = $cites->get();
        $pdf = PDF::loadView('external.pdf', [
            'cites' => $cites,
            //'professional' => Auth::user()->profile->professionalProfile,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->input('filter4') != "" ? $request->input('filter4') : null,
            'filter5' => $request->input('filter5') != "" ? $request->input('filter5') : null,
            'filter6' => $request->input('filter6') != "" ? $request->input('filter6') : null,
            'companyLogo' => $companyLogo->value
        ]);

        return $pdf->download('cites.pdf');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function _show(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $cite = Cite::find($request->id);
        $calendarEvent = $cite->calendarEvent;
        if ($user->isAbleTo('patient-profile') && $calendarEvent->patientProfiles->contains($user->profile->patientProfile)) {
            return redirect('/profile/events/'.$calendarEvent->id);
        }
        
        return view('pages.showcite')->with([
            'cite' => $cite,
            'calendarEvent' => $calendarEvent]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $cite = Cite::find($request->id);
        $calendarEvent = $cite->calendarEvent;
        return view('pages.showcite')->with([
            'cite' => $cite,
            'calendarEvent' => $calendarEvent]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'document' => 'mimes:pdf,doc,docx,odt|max:10240'
        ]);
        $cite = Cite::find($request->id);
        
        $practice = Practice::find($request->input('practice'));

        $calendarEvent = $cite->calendarEvent;
        $cite->update([
            'assisted' => $request->input('assisted'),
            'isVirtual' => $request->input('isVirtual'),
            'paid' => $request->input('paid')
        ]);
        if ($cite->practice != $practice) {
            $cite->practice()->dissociate();
            $cite->practice()->associate($practice);
        }
        $patarr = $calendarEvent->patientProfiles->toArray(['id']);
        $medicalHistory = MedicalHistory::where('professional_profile_id', $calendarEvent->professional_profile_id)->where('patient_profile_id', $patarr[0])->first();
        //dd($medicalHistory);
        if ($request->has('document')) {
            if ($request->hasFile('document') && $request->file('document')->isValid()) {
                try {
                    $path = Storage::putFile('files/histories/' . $medicalHistory->id, $request->file('document'));
                    FileVault::encrypt($path);
                    $document = new Document();
                    $document->name = $request->file('document')->getClientOriginalName();
                    $document->path = $path;
                    $document->medical_history_id = $medicalHistory->id;
                    $document->cite_id = $cite->id;
                    $document->save();
                } catch (\Throwable $th) {
                    return back()->withErrors('error', $th->getMessage());
                }
            }
        }
        
        if ($calendarEvent->approved == 0) {
            $calendarEvent->approved = $request->input('approved');
        }
        

        if ($request->has('resume') && !empty($request->resume)) {
            $cite->resume = encrypt($request->resume);
        }
        $cite->save();
        $calendarEvent->save();
        $patients = $cite->calendarEvent->patientProfiles;
        foreach ($patients as $patient) {
            if ($request->input('approved') != 0) {
                $data = array(
                    'fullname' => $patient->profile->user->name . ' ' . $patient->profile->user->lastname,
                    'email' => $patient->profile->user->email
                );
                Mail::send('external.approved', [
                    'event' => $cite->calendarEvent,
                    'reminder' => $calendarEvent->reminder,
                    'patient' => $patient
                ], function ($message) use ($data) {
                    $companyName = DB::table('settings')->where('name', 'company-name')->first(['value']);
                    $message->to($data['email'], $data['fullname'])->subject($companyName->value.' | Recordatorio de turno para el d??a ');
                    $message->from('sistema@consulta2.com', 'Consulta2');
                });
            }

            if ($request->input('assisted') == 1) {
                if ($patient->medicalHistories->where('professional_profile_id', $calendarEvent->professional_profile_id) == null) {
                    $medical_history = new MedicalHistory();
                    $medical_history->indate = now();
                    $medical_history->visitreason = encrypt("** Sin datos **");
                    $medical_history->diagnosis = encrypt("** Sin datos **");
                    $medical_history->clinical_history = encrypt("** Sin datos **");
                    $medical_history->psicological_history = encrypt("**Sin datos**");
                    $medical_history->patient_profile_id = $patient->id;
                    $medical_history->professional_profile_id = $calendarEvent->professionalProfile->id;
                    $medical_history->save();
                    $cite->medical_history_id = $medical_history->id;
                    $cite->save();
                } else {
                    $cite->medical_history_id = $patient-> medicalHistories->where('professional_profile_id', $calendarEvent->professional_profile_id)->first()->id;
                    $cite->save();
                }
                
            }
        }
        
        
        return back()->withStatus(__('Datos de consulta actualizados.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cite $Cite)
    {
        //
    }

}