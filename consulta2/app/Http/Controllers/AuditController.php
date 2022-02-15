<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index() {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            $audits = Audit::where('id', '>', 0)->orderByDesc('created_at')->sortable()->paginate(10);
            return view('admin.audit_log')->with(['audits' => $audits]);
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
            return view('admin.settings');
        } else {
            return abort(404);
        }
    }

    public function settingsUpdate(Request $request) {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            $settings = DB::table('settings')->get();
            foreach ($settings as $name => $setting) {
                foreach ($request->all() as $key => $value) {
                    if ($key = $name) {
                        $setting = $value;
                    }
                }
            }
            return back()->withStatus('status', 'Configuración actualizada.');
        } else {
            return abort(404);
        }
    }
}
