<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index() {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('admin-profile') /* && $user->isAbleTo('AuditController@index') */) {
            $audits = Audit::all();
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
}
