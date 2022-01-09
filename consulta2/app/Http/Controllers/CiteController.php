<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Cite;
use App\Models\Practice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cites = CalendarEvent::where('id', '>', 0)->where('professional_profile_id', Auth::user()->id);
        if ($request->has('filter1') && $request->filter1 != "") {
            $today = date_create('now');
            $cites = $cites->where('start', $request->filter1, $today);
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            $name = $this->sec->clean($request->filter2);
            $cites = $cites->where('title', 'like', '%' . $name . '%');
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
        //dd($query);
        return view('pages.cites')->with([
            'cites' => $cites,
            'professional' => Auth::user()->profile->professionalProfile,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->input('filter4') != "" ? $request->input('filter4') : null,
            'filter5' => $request->input('filter5') != "" ? $request->input('filter5') : null,
            'filter6' => $request->input('filter6') != "" ? $request->input('filter6') : null,
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
        $filter = $request->query('filter');
        $filter2 = $request->query('filter2');
        if (!empty($filter)) {
            if (!empty($filter2)) {
                $query = DB::table('calendar_events')
                    ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
                    ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
                    ->where('calendar_events.start', 'like', '%'.$filter.'%')
                    ->where('calendar_events.title', 'like', '%'.$filter2.'%')
                    ->where('calendar_events.professional_profile_id', auth()->user()->id)
                    ->get();
            } else {
                $query = DB::table('calendar_events')
                    ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
                    ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
                    ->where('calendar_events.start', 'like', '%'.$filter.'%')
                    ->where('calendar_events.professional_profile_id', auth()->user()->id)
                    ->get();
            }
        } else if (!empty($filter2)) {
            $query = DB::table('calendar_events')
            ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
            ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', 'professional_profiles.id')
            ->where('calendar_events.title', 'like', '%'.$filter2.'%')
            ->where('calendar_events.professional_profile_id', auth()->user()->id)
            ->get();
        } else {
            $query = DB::table('calendar_events')
            ->join('cites', 'calendar_events.id', '=', 'cites.calendar_event_id')
            ->join('professional_profiles', 'calendar_events.professional_profile_id' ,'=', auth()->user()->id)
            ->where('calendar_events.professional_profile_id', auth()->user()->id)
            ->get();
        }
        
        //dd($query);
        $view = view('pages.cites')->with([
            'filter' => $filter,
            'filter2' => $filter2,
            'cites' => $query
        ]);
        $pdf = app('dompdf.wrapper');
        $pdf->loadView($view->render());

        // download PDF file with download method
        return $pdf->download('informe_sesiones.pdf');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cite  $Cite
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $cite = Cite::find($request->id);
        $calendarEvent = $cite->calendarEvent;
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
        $cite = Cite::find($request->id);
        
        $practice = Practice::find($request->input('practice'));

        $calendarEvent = $cite->calendarEvent;
        $cite->update([
            'assisted' => $request->input('assisted'),
            'isVirtual' => $request->input('isVirtual')
        ]);
        if ($cite->practice != $practice) {
            $cite->practice()->dissociate();
            $cite->practice()->associate($practice);
        }
        
        $calendarEvent->update([
            'approved' => $request->input('approved')
        ]);
        $patient = $cite->calendarEvent->patientProfiles->first();
        $cite->save();
        $calendarEvent->save();
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
                            $message->to($data['email'], $data['fullname'])->subject('Consulta2 | Recordatorio de turno para el día ');
                            $message->from('sistema@consulta2.com', 'Consulta2');
                        });
        }
        return back()->withStatus(__('Datos de sesión actualizados.'));
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