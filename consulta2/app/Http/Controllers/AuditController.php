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
            $audits = Audit::where('event', '<>', 'deleted');
            if ($request->has('filter1') && $request->filter1 != "") {
                $audits = $audits->where('created_at', '>=', $request->filter1);
            }
            
            if ($request->has('filter2') && $request->filter2 != "all") {
                $audits = $audits->where('event', $request->filter2);
            }
            if ($request->has('filter3') && $request->filter3 != "") {
                $audits = $audits->where('created_at', '<=', $request->filter3);
            }
            if ($request->has('filter4') && $request->filter4 != "") {
                $audits = $audits->where('auditable_type', 'like', '%'.strtolower($request->filter4).'%');
            }
            $audits = $audits->orderByDesc('created_at')->sortable()->paginate(10);
            return view('admin.audit_log')->with(['audits' => $audits,
                'filter1' => $request->filter1 != "" ? $request->filter1 : "",
                'filter2'=> $request->filter2 != "" ? $request->filter2 : "",
                'filter3' => $request->filter3 != "" ? $request->filter3 : "",
                'filter4' => $request->filter4 != "" ? $request->filter4 : "",
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

            $companyLogo = DB::table('settings')->where('name', 'company-logo')->first('value');
            $companyName = DB::table('settings')->where('name', 'company-name')->first('value');
            $companyEmail = DB::table('settings')->where('name', 'company-email')->first('value');
            $companyPhone = DB::table('settings')->where('name', 'company-phone')->first('value');
            $cuit = DB::table('settings')->where('name', 'cuit')->first('value');
            return view('admin.settings')->with([
                'timezone' => $timezone->value,
                'calendar_range' => $calendar_range->value,
                'recharge' => $recharge->value,
                'patient_register' => $patient_register->value,
                'professional_register' => $professional_register->value,
                'companyLogo' => $companyLogo->value,
                'companyName' => $companyName->value,
                'companyEmail' => $companyEmail->value,
                'companyPhone' => $companyPhone->value,
                'cuit' => $cuit->value
            ]);
        } else {
            return abort(404);
        }
    }

    public function settingsUpdate(Request $request) {
        $validatedData = $request->validate([
            'company-logo' => 'nullable|mimes:jpg,png,jpeg,gif,svg|max:10240'
        ]);

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
            
            $companyName = DB::table('settings')->where('name', 'company-name')->update(
                [
                    'value' => $request->input('company-name')
                ]
            );
            $companyEmail = DB::table('settings')->where('name', 'company-email')->update(
                [
                    'value' => $request->input('company-email')
                ]
            );
            if ($request->hasFile('company-logo')) {
                $companyLogo = DB::table('settings')->where('name', 'company-logo')->update(
                    [
                        'value' => $request->file('company-logo')->store('public/images')
                    ]
                );
            }
            $companyPhone = DB::table('settings')->where('name', 'company-phone')->update(
                [
                    'value' => $request->input('company-phone')
                ]
            );
            $companyPhone = DB::table('settings')->where('name', 'cuit')->update(
                [
                    'value' => $request->input('cuit')
                ]
            );
            return back()->withStatus('status', 'Configuración actualizada.');
        } else {
            return abort(404);
        }
    }
}
