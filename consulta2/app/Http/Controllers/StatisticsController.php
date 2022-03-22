<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Coverage;
use App\Models\Lifesheet;
use App\Models\PatientProfile;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class StatisticsController extends Controller
{
    public function index(Request $request) {
        $user = User::find(auth()->user()->id);
        return view('pages.statistics');
    }

    public function loadAgeRange(Request $request) {
        if ($request->ajax()) {
            $user = User::find(auth()->user()->id);
            $professionalProfile = $user->profile->professionalProfile;
            $calendarEvents = CalendarEvent::where('active', true)->where('professional_profile_id', $professionalProfile->id);
            if ($request->has('datestart') && $request->has('dateend')) {
                $calendarEvents = $calendarEvents->where('start', '>=', $request->datestart)->where('start', '<=', $request->dateend);
            }
            $calendarEvents = $calendarEvents->get();
            if ($calendarEvents->count() == 0) {
                return response()->json(["status" => "error"]);
            }
            $range09 = 0;
            $range1019 = 0;
            $range2029 = 0;
            $range3039 = 0;
            $range4049 = 0;
            $range5059 = 0;
            $range6069 = 0;
            $rangem70 = 0;
            foreach ($calendarEvents as $calendarEvent) {
                if (count($calendarEvent->patientProfiles) > 0) {
                    foreach ($calendarEvent->patientProfiles as $patient) {
                        $d1 = new DateTime('now');
                        $d2 = new DateTime($patient->profile->bornDate);

                        $difference = $d2->diff($d1);

                        $diff = $difference->y;
                        if ($diff >= 0 && $diff <= 9) {
                            $range09 += 1;
                        } elseif ($diff >= 10 && $diff <= 19) {
                            $range1019 += 1;
                        } elseif ($diff >= 20 && $diff <= 29) {
                            $range2029 += 1;
                        } elseif ($diff >= 30 && $diff <= 39) {
                            $range3039 += 1;
                        } elseif ($diff >= 40 && $diff <= 49) {
                            $range4049 += 1;
                        } elseif ($diff >= 50 && $diff <= 59) {
                            $range5059 += 1;
                        } elseif ($diff >= 60 && $diff <= 69) {
                            $range6069 += 1;
                        } elseif ($diff >= 70) {
                            $rangem70 += 1; //ojala hubiera un switch para estas situaciones, o lo hay? ni la organizacion lo sabia
                        }
                    }
                }
            }
            //retornar array de 8 lugares (indizado 0-7)
            return response()->json([
                "status" => "success",
                "series" => [$range09, $range1019, $range2029, $range3039, $range4049, $range5059, $range6069, $rangem70]
            ]);
        }
    }

    public function loadCoverageComparison(Request $request) {
        if ($request->ajax()) {
            $user = User::find(auth()->user()->id);
            $professionalProfile = $user->profile->professionalProfile;

            $calendarEvents = CalendarEvent::where('active', true)->where('professional_profile_id', $professionalProfile->id);
            if ($request->has('datestart') && $request->has('dateend')) {
                $calendarEvents = $calendarEvents->where('start', '>=', $request->datestart)->where('start', '<=', $request->dateend);
            }
            $calendarEvents = $calendarEvents->get();
            $totalEvents = $calendarEvents->count();
            
            if ($totalEvents == 0) {
                return response()->json(['status' => 'void']);
            }

            //hago un array donde cada indice corresponde a una cobertura medica soportada por el profesional
            $countingarray = array();
            $namesarray = array();
            foreach ($professionalProfile->coverages as $coverage) {
                $countingarray[$coverage->name] = 0;
                array_push($namesarray, $coverage->name);
            }
            foreach ($calendarEvents as $calendarEvent) {
                if (count($calendarEvent->patientProfiles) > 0) {
                    foreach ($calendarEvent->patientProfiles as $patient) { //itero por cada paciente registrado para el profesional
                        if ($professionalProfile->coverages->contains($patient->lifesheet->coverage)) { //obra social soportada!
                            $countingarray[$patient->lifesheet->coverage->name] += 1;
                        }
                    }
                }
            }
            $series = array();
            $labels = array();
            foreach ($countingarray as $key => $value) {
                array_push($series, ($value/$totalEvents)*100);
                array_push($labels, (string)(($value / $totalEvents) * 100).'%');
            }
            //retornar array bidimensional de 5 lugares (indizado 0-4)
            //una fila para los porcentajes en formato entero N%
            //otra fila para los nombres de las etiquetas
            return response()->json([
                "status" => "success",
                "names" => $namesarray,
                "labels" => $labels,
                "series" => $series
            ]);
        }
    }

    public function createPDF(Request $request) {
        $pdf = PDF::loadView('pages.statistics_pdf');
        return $pdf->download("reporte_estadistico.pdf");
    }
}
