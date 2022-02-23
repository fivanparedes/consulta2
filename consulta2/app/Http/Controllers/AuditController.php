<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index(Request $request) {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            $audits = Audit::all()->toQuery();
            if ($request->has('filter1') && $request->filter1 != "") {
                $audits = $audits->where('created_at', 'like', '%'.$request->filter1.'%');
            }
            if ($request->has('filter2') && $request->filter2 != "all") {
                $audits = $audits->where('event', $request->filter2);
            }
            $audits = $audits->orderByDesc('created_at')->sortable()->paginate(10);
            return view('admin.audit_log')->with(['audits' => $audits,
                'filter1' => $request->filter1 != "" ? $request->filter1 : "",
                'filter2'=> $request->filter2 != "" ? $request->filter2 : ""
        ]);
        } else {
            return abort(404);
        }
    }

    public function show(Request $request) {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            $audit = Audit::find($request->id);
            return view('admin.audit_show')->with(['audit' => $audit]);
        } else {
            return abort(404);
        }
    }

    public function config() {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            
            return view('admin.config');
        } else {
            return abort(404);
        }
    }

    public function settings() {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            $timezone = DB::table('settings')->where('name', 'timezone')->first('value');
            $calendar_range = DB::table('settings')->where('name', 'calendar_range')->first('value');
            $recharge = DB::table('settings')->where('name', 'recharge')->first('value');
            $patient_register = DB::table('settings')->where('name', 'patient_register')->first('value');
            $professional_register = DB::table('settings')->where('name', 'professional_register')->first('value');
            return view('admin.settings')->with([
                'timezone' => $timezone->value,
                'calendar_range' => $calendar_range->value,
                'recharge' => $recharge->value,
                'patient_register' => $patient_register->value,
                'professional_register' => $professional_register->value
            ]);
        } else {
            return abort(404);
        }
    }

    public function settingsUpdate(Request $request) {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            $timezone = DB::table('settings')->where('name', 'timezone')->update([
                'value' => $request->timezone,
            ]);
            $calendar_range = DB::table('settings')->where('name', 'calendar_range')->update([
                'value' => $request->dayrange
            ]);
            $recharge = DB::table('settings')->where('name', 'recharge')->update([
                'value' => $request->charge
            ]);
            $patient_register = DB::table('settings')->where('name', 'patient_register')->update([
                'value' => $request->input('register-patient')
            ]);
            $professional_register = DB::table('settings')->where('name', 'professional_register')->update(
                [
                    'value' => $request->input('register-professional')
                ]
            );
            return back()->withStatus('status', 'Configuración actualizada.');
        } else {
            return abort(404);
        }
    }
}
