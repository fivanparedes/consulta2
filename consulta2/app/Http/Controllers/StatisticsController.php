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

class StatisticsController extends Controller
{
    public function loadAgeRange(Request $request) {
        if ($request->ajax()) {
            $user = User::find(auth()->user()->id);
            $professionalProfile = $user->profile->professionalProfile;
            $calendarEvents = CalendarEvent::where('professional_profile_id', $professionalProfile->id)->get();
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
                        $dStart = new DateTime($patient->profile->bornDate);
                        $dEnd  = new DateTime('now');
                        $dDiff = $dStart->diff($dEnd);
                        $diff = (int)$dDiff->format('%r%a');
                        if ($diff >= 0 || $diff <= 9) {
                            $range09 += 1;
                        } elseif ($diff >= 10 || $diff <= 19) {
                            $range1019 += 1;
                        } elseif ($diff >= 20 || $diff <= 29) {
                            $range2029 += 1;
                        } elseif ($diff >= 30 || $diff <= 39) {
                            $range3039 += 1;
                        } elseif ($diff >= 40 || $diff <= 49) {
                            $range4049 += 1;
                        } elseif ($diff >= 50 || $diff <= 59) {
                            $range5059 += 1;
                        } elseif ($diff >= 60 || $diff <= 69) {
                            $range6069 += 1;
                        }
                        if ($diff >= 70) {
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
            $calendarEvents = CalendarEvent::where('professional_profile_id', $professionalProfile->id)->get();
            $totalEvents = $calendarEvents->count();
            
            if ($totalEvents == 0) {
                return response()->json(['status' => 'void']);
            }

            //hago un array donde cada indice corresponde a una cobertura medica soportada por el profesional
            $countingarray = array();
            foreach ($professionalProfile->coverages as $coverage) {
                $countingarray[$coverage->name] = 0;
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

            //En teoria estos son los quintiles, pero no se como se dice en ingles xd
            $onefiver = ["Particular",$countingarray["Particular"]];
            $secondfiver = array();
            $thirdfiver = array();
            $fourthfiver = array();
            $fifthfiver = ["others", 0];

            $strippedarray = array();
            foreach ($countingarray as $key => $value) {
                if ($key != "Particular") {
                    $strippedarray[$key] = $value;
                }
            }
            sort($strippedarray);
            array_reverse($strippedarray);
            
            //primera vez que uso el atributo ->key, ojala funcione
            $secondfiver = isset($strippedarray[0]) ? [$strippedarray[0]->key, $strippedarray[0]] : ["-", 0];
            $thirdfiver = isset($strippedarray[1]) ? [$strippedarray[1]->key, $strippedarray[1]] : ["-", 0];
            $fourthfiver = isset($strippedarray[2]) ? [$strippedarray[2]->key, $strippedarray[2]] : ["-", 0];
            
            if (count($strippedarray) > 3) {
                for ($i=3; $i < count($strippedarray) ; $i++) { 
                    $fifthfiver[1] += $strippedarray[$i];
                }
            } else {
                $fifthfiver = ["-", 0];
            }
            

            //retornar array bidimensional de 5 lugares (indizado 0-4)
            //una fila para los porcentajes en formato entero N%
            //otra fila para los nombres de las etiquetas
            return response()->json([
                "status" => "success",
                "labels" => [$onefiver[0], $secondfiver[0], $thirdfiver[0], $fourthfiver[0], $fifthfiver[0]],
                "series" => [($onefiver[1]/$totalEvents)*100, ($secondfiver[1]/$totalEvents)*100, ($thirdfiver[1]/$totalEvents)*100, ($fourthfiver[1]/$totalEvents)*100, ($fifthfiver[1]/$totalEvents)*100]
            ]);
        }
    }
}
