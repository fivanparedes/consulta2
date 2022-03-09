<?php

namespace App\Http\Controllers;

use App\Models\BusinessHours;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use App\Models\ConsultType;
use App\Models\Permission;
use App\Models\ProfessionalProfile;
use App\Models\Profile;
use App\Models\Province;
use App\Models\Specialty;
use App\Models\User;
use GrahamCampbell\SecurityCore\Security;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade as PDF;

class ProfessionalController extends Controller
{
    private $sec;
    public function __construct()
    {
        $this->sec = Security::create(["\/", "\\", "\(", "\)", "\;"], "\0");
    }

    public function getFilteredProfessionals(Request $request) {
        if ($request->ajax()) {
            $list = ProfessionalProfile::where('status', '<>', 0);
            if ($request->specialty != 0) {
                $list = $list->where('specialty_id', $request->specialty);
            }
            if ($request->location != "all") {
                if ($request->location == "city") {
                    $profs = Profile::where('city_id', Auth::user()->profile->city->id)->get(['id'])->toArray();
                    $list = $list->whereIn('profile_id', $profs);
                } elseif ($request->location == "province") {
                    $cities = City::where('province_id', Auth::user()->profile->city->province->id)->get(['id'])->toArray();
                    $profs = Profile::whereIn('city_id', $cities)->get(['id'])->toArray();
                    $list = $list->whereIn('profile_id',$profs);
                }
            }
            $list = $list->get();
            $collection = array();
            
            foreach ($list as $item) {
                $coverages = array();
                foreach ($item->coverages as $coverage) {
                    array_push($coverages, $coverage->name);
                }
                $obj = [
                    'id' => $item->id,
                    'fullname' => $item->profile->user->name . ' ' . $item->profile->user->lastname,
                    'pfp' => $item->profile->user->pfp != '' ? asset('/storage/images/' . explode('/', $item->profile->user->pfp)[2]) : asset('light-bootstrap/img/default-avatar.png'),
                    'specialty' => $item->specialty->displayname . ' (' . $item->field . ')',
                    'institution' => $item->institution_id != 1 ? 'Lugar de consulta: ' . $item->institution->name : 'Independiente / Consultorio propio',
                    'coverages' => implode(" / ", $coverages),
                
                ];
                array_push($collection, $obj);
            }
            return response()->json(['status' => 'success', 'content' => $collection]);
        }
    }

    public function create(Request $request) {
        $user = User::find(auth()->user()->id);

        return view('admin.professionals_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|filled|max:60',
            'user_lastname' => 'required|string|filled|max:60',
            'user_dni' => 'required|numeric|unique:users,dni',
            'user_email' => 'required|email:strict|unique:users,email',
            'bornDate' => 'required|date_format:Y-m-d|before:' . date('Y-m-d'),
            'gender' => 'required|string',
            'phone' => 'required',
            'address' => 'required|string|filled|max:100',
            'licensePlate' => 'required|string|filled|max:40',
            'status' => 'required|numeric|max:1',
            'field' => 'required|string|filled|max:20',
        ]);
        $user = User::find(auth()->user()->id);

        $patuser = new User();
        $patuser->name = $request->user_name;
        $patuser->lastname = $request->user_lastname;
        $patuser->dni = $request->user_dni;
        $patuser->email = $request->user_email;
        $patuser->password = Hash::make($request->user_dni);
        $patuser->save();
        $patuser->attachRole('Professional');

        $patprofile = new Profile();
        $patprofile->bornDate = date_create($request->bornDate);
        $patprofile->gender = $request->gender;
        $patprofile->phone = $request->phone;
        $patprofile->address = $request->address;
        $patprofile->city_id = $request->city;
        $patprofile->user_id = $patuser->id;
        $patprofile->save();

        $patientProfile = new ProfessionalProfile();
        $patientProfile->licensePlate = $request->licensePlate;
        $patientProfile->status = 1;
        $patientProfile->field = $request->field;
        $patientProfile->specialty_id = $request->specialty;
        $patientProfile->institution_id = $user->hasPermission('institution-profile') ? $user->institutionProfile->id : ($request->has('institution_id') ? $request->institution_id : 1);
        $patientProfile->profile_id = $patprofile->id;
        $patientProfile->save();

        return redirect('/manage/professionals');
    }
    public function index(Request $request)
    {

        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('patient-profile')) {
            $_professionals = ProfessionalProfile::where('status', '<>', 0)->get();
            return view('professionals.index')->with(['professionals' => $_professionals]);
        }
        $professionals = ProfessionalProfile::where('id', '>=', 1);
        if ($user->isAbleTo('institution-profile')) {
            $professionals = $professionals->where('institution_id', $user->institutionProfile->id);
        }
        
        
        if ($request->has('filter1') && $request->filter1 != "") {
            $dni = $this->sec->clean($request->filter1);
            $users = User::where('dni','like', '%' . $dni . '%')->get(['id'])->toArray();
            $profiles = Profile::whereIn('user_id', $users)->get(['id'])->toArray();
            $professionals = $professionals->whereIn('profile_id', $profiles);
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            $lastname = $this->sec->clean($request->filter2);
            $users = User::where('lastname', 'like', '%' . $lastname . '%')->get(['id'])->toArray();
            $profiles = Profile::whereIn('user_id', $users)->get(['id'])->toArray();
            $professionals = $professionals->whereIn('profile_id', $profiles);
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $name = $this->sec->clean($request->filter3);
            $users = User::where('name', 'like', '%' . $name . '%')->get(['id'])->toArray();
            $profiles = Profile::whereIn('user_id', $users)->get(['id'])->toArray();
            $professionals = $professionals->whereIn('profile_id', $profiles);
        }
        if ($request->has('filter4') && $request->filter4 != "") {
            $spec = $this->sec->clean($request->filter4);
            $specs = Specialty::where('displayname', 'like', '%' . $spec . '%')->get(['id'])->toArray();
            $professionals = $professionals->whereIn('specialty_id', $specs);
        }
        if ($request->filter5 != "") {
            $stat = $this->sec->clean($request->filter5);
            $professionals = $professionals->where('status', $stat);
        }
        
        $professionals = $professionals->sortable()->paginate(10);
        return view('admin.professionals')->with([
            'professionals' => $professionals,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->input('filter4') != "" ? $request->input('filter4') : null,
            'filter5' => $request->input('filter5') != "" ? $request->input('filter5') : null,
        ]);
    }

    public function createPDF(Request $request) {
        $user = User::find(auth()->user()->id);
        if ($user->isAbleTo('patient-profile')) {
            $_professionals = ProfessionalProfile::where('status', '<>', 0)->get();
            $companyLogo = DB::table('settings')->where('name', 'company-logo')->first(['value']);
            $pdf = PDF::loadView('professionals.pdf',['professionals' => $_professionals, 'companyLogo' => $companyLogo->value]);
            return $pdf->download('profesionales.pdf');
        }
        $professionals = ProfessionalProfile::where('id', '>=', 1);
        if ($user->isAbleTo('institution-profile')) {
            $professionals = $professionals->where('institution_id', $user->institutionProfile->id);
        }


        if ($request->has('filter1') && $request->filter1 != "") {
            $dni = $this->sec->clean($request->filter1);
            $users = User::where('dni', 'like', '%' . $dni . '%')->get(['id'])->toArray();
            $profiles = Profile::whereIn('user_id', $users)->get(['id'])->toArray();
            $professionals = $professionals->whereIn('profile_id', $profiles);
        }
        if ($request->has('filter2') && $request->filter2 != "") {
            $lastname = $this->sec->clean($request->filter2);
            $users = User::where('lastname', 'like', '%' . $lastname . '%')->get(['id'])->toArray();
            $profiles = Profile::whereIn('user_id', $users)->get(['id'])->toArray();
            $professionals = $professionals->whereIn('profile_id', $profiles);
        }
        if ($request->has('filter3') && $request->filter3 != "") {
            $name = $this->sec->clean($request->filter3);
            $users = User::where('name', 'like', '%' . $name . '%')->get(['id'])->toArray();
            $profiles = Profile::whereIn('user_id', $users)->get(['id'])->toArray();
            $professionals = $professionals->whereIn('profile_id', $profiles);
        }
        if ($request->has('filter4') && $request->filter4 != "") {
            $spec = $this->sec->clean($request->filter4);
            $specs = Specialty::where('displayname', 'like', '%' . $spec . '%')->get(['id'])->toArray();
            $professionals = $professionals->whereIn('specialty_id', $specs);
        }
        if ($request->filter5 != "") {
            $stat = $this->sec->clean($request->filter5);
            $professionals = $professionals->where('status', $stat);
        }

        $professionals = $professionals->sortable()->paginate(10);
        $companyLogo = DB::table('settings')->where('name', 'company-logo')->first(['value']);
        $pdf = PDF::loadView('professionals.pdf',[
            'professionals' => $professionals,
            'filter1' => $request->input('filter1') != "" ? $request->input('filter1') : null,
            'filter2' => $request->input('filter2') != "" ? $request->input('filter2') : null,
            'filter3' => $request->input('filter3') != "" ? $request->input('filter3') : null,
            'filter4' => $request->input('filter4') != "" ? $request->input('filter4') : null,
            'filter5' => $request->input('filter5') != "" ? $request->input('filter5') : null,
            'companyLogo' => $companyLogo->value
        ]);
        return $pdf->download('profesionales.pdf');
    }

    public function edit(Request $request)
    {
        $professional = ProfessionalProfile::find($request->id);
        return view('admin.professionals_edit')->with(['professional' => $professional]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'user_name' => 'string|max:60',
            'user_lastname' => 'string|max:60',
            'user_dni' => 'numeric',
            'user_email' => 'email:strict',
            'bornDate' => 'required|date_format:Y-m-d|before:' . date('Y-m-d'),
            'gender' => 'required|string',
            'phone' => 'required',
            'address' => 'required|string|filled|string|max:100',
            'licensePlate' => 'required|string|filled|max:40',
            'status' => 'required|numeric|max:1',
            'field' => 'required|string|filled|max:20',
        ]);
        $user = User::find(auth()->user()->id);
        $professional = ProfessionalProfile::find($request->id);

        $patuser = $professional->profile->user;
        if ($patuser->name != $request->user_name) {
            $patuser->name = $request->user_name;
        }
        if ($patuser->lastname != $request->lastname) {
            $patuser->lastname = $request->user_lastname;
        }
        if ($patuser->dni != $request->user_dni) {
            $patuser->dni = $request->user_dni;
        }
        if ($patuser->email != $request->user_email) {
            $patuser->email = $request->user_email;
        }
        
        $patuser->save();

        $patprofile = $professional->profile;
        $patprofile->bornDate = date_create($request->bornDate);
        $patprofile->gender = $request->gender;
        $patprofile->phone = $request->phone;
        $patprofile->address = $request->address;
        $patprofile->city_id = $request->city;
        $patprofile->user_id = $patuser->id;
        $patprofile->save();

        $professional->licensePlate = $request->licensePlate;
        $professional->field = $request->field;
        $professional->specialty_id = $request->specialty;
        $professional->status = $request->input('status');
        $professional->institution_id = $user->hasPermission('institution-profile') ? $user->institutionProfile->id : ($request->has('institution_id') ? $request->institution_id : 1);
        $professional->save();
        $turnperm = Permission::where('name', 'receive-consults')->first();
        if ($request->input('status') == 1 && !$professional->profile->user->isAbleTo('receive-consults')) {
            $professional->profile->user->attachPermission($turnperm);
            $professional->save();
        } elseif ($request->input('status') == 0 && $professional->profile->user->isAbleTo('receive-consults')) {
            $professional->profile->user->detachPermission($turnperm);
            $professional->save();
        }


        return redirect('/manage/professionals');
    }

    public function show(Request $request)
    {
        $covered = false;
        $_prof = Profile::where('user_id', $request->id)->first();

        $_professional = ProfessionalProfile::find($request->id);

        if (!$_professional->profile->user->isAbleTo('receive-consults')) {
            return abort(403);
        }

        $patient = Auth::user()->profile->patientProfile;

        $_consults = $_professional->consultTypes->where('visible', true);

        $_workingHours = $_professional->businessHours()->get();
        if ($patient->lifesheet->coverage->id != 1) {
            if ($_professional->coverages->contains($patient->lifesheet->coverage)) {
                $covered = true;
            }
        }

        return view('professionals.show')
            ->with([
                'professional' => $_professional,
                'patient' => $patient,
                'covered' => $covered,
                'workingHours' => $_workingHours,
                'consulttypes' => $_consults
            ]);
    }
}
