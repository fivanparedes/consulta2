<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\InstitutionProfile;
use App\Models\ProfessionalProfile;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Barryvdh\DomPDF\Facade as PDF;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        if (!$user->hasRole('Admin')) {
            return abort(404);
        }
        $institutions = InstitutionProfile::where('id', '>', 0);
        
        $institutions = $institutions->sortable()->paginate(10);
        return view('institutions.index')->with(['institutions' => $institutions]);
    }

    public function createPDF(Request $request) {
        $institutions = InstitutionProfile::all();
        $pdf = PDF::loadView('institutions.pdf',['institutions' => $institutions]);
        return $pdf->download('instituciones.pdf');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(auth()->user()->id);
        if (!$user->hasRole('Admin')) {
            return abort(404);
        }
        return view('institutions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|filled|max:20',
            'user_lastname' => 'required|string|filled|max:60',
            'user_dni' => 'required|unique:users,dni|numeric',
            'name' => 'required|string|filled|max:70',
            'description' => 'required|string|filled|max:100',
            'address' => 'required|string|filled|max:100',
            'phone' => 'required|numeric|max:40',
            'city' => 'required|numeric'
        ]);
        $user = User::find(auth()->user()->id);
        if (!$user->isAbleTo('admin-profile') && !$user->isAbleTo('institution-profile')) {
            return abort(404);
        }
        try {
            

            $inst_user = new User();
            $inst_user->name = $request->user_name;
            $inst_user->lastname = $request->user_lastname;
            $inst_user->dni = $request->user_dni;
            $inst_user->email = $request->user_email;
            $inst_user->password = Hash::make($request->user_dni);
            $inst_user->save();
            $inst_user->attachRole('Institution');

            $institutionProfile = new InstitutionProfile();
            $institutionProfile->name = $request->name;
            $institutionProfile->description = $request->description;
            $institutionProfile->address = $request->address;
            $institutionProfile->phone = $request->phone;
            $institutionProfile->city_id = $request->city;
            $institutionProfile->active = true;
            $institutionProfile->user()->associate($inst_user);
            $institutionProfile->save();

        } catch (Throwable $th) {
            return back()->withErrors('error', 'Error al guardar.');
            
        }
        return redirect('/institutions');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find(auth()->user()->id);
        $institution = InstitutionProfile::find($id);
        if ($user->hasRole('Patient')) {
            return view('institutions.show')->with(['institution' => $institution]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find(auth()->user()->id);
        $institution = InstitutionProfile::find(base64_decode(base64_decode($id)));
        if ($user->isAbleTo('admin-profile')) {
            return view('institutions.edit')->with(['institution' => $institution]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|filled|max:70',
            'description' => 'required|string|filled|max:100',
            'address' => 'required|string|filled|max:100',
            'phone' => 'required|numeric',
            'city' => 'required|numeric'
        ]);
        //dd($request->all());
        $user = User::find(auth()->user()->id);
        if (!$user->isAbleTo('admin-profile') && !$user->isAbleTo('institution-profile')) {
            return abort(404);
        }
        try {
            $institutionProfile = InstitutionProfile::find($id);
            
            /* $inst_user = $institutionProfile->user;
            $inst_user->name = $request->user_name;
            $inst_user->lastname = $request->user_lastname;
            $inst_user->dni = $request->user_dni;
            $inst_user->email = $request->user_email;
            $inst_user->password = Hash::make($request->user_dni);
            $inst_user->save(); */
            //dd($institutionProfile);
            $institutionProfile->name = $request->name;
            $institutionProfile->description = $request->description;
            $institutionProfile->address = $request->address;
            $institutionProfile->phone = $request->phone;
            $institutionProfile->city_id = $request->city;
            $institutionProfile->active = $request->active == 1 ? true : false;
            $institutionProfile->save();
        } catch (Throwable $th) {
            //dd($th);
            return back()->withErrors('error', 'Error al guardar.');
        }
        return redirect('/institutions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        InstitutionProfile::destroy($id);
        return redirect('/institutions');
    }

    public function list(Request $request) {
        $user = User::find(auth()->user()->id);
        if (!$user->hasRole('Patient')) {
            return abort(404);
        }
        return view('institutions.list');
    }

    public function getFilteredInstitutions(Request $request)
    {
        if ($request->ajax()) {
            $list = InstitutionProfile::where('id', '>', 1);
            if ($request->location != "all") {
                if ($request->location == "city") {
                    $list = $list->where('city_id', Auth::user()->profile->city->id);
                } elseif ($request->location == "province") {
                    $cities = City::where('province_id', Auth::user()->profile->city->province->id)->get(['id'])->toArray();
                    $list = $list->whereIn('city_id', $cities);
                }
            }
            $list = $list->get();
            $collection = array();

            foreach ($list as $item) {
                $specialties = array();
                foreach ($item->professionalProfiles as $professional) {
                    array_push($specialties, $professional->specialty->displayname);
                }
                $obj = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'address' => $item->address,
                    'specialties' => implode(" / ", $specialties),
                    'professionals' => $item->professionalProfiles->count()

                ];
                array_push($collection, $obj);
            }
            return response()->json(['status' => 'success', 'content' => $collection]);
        }
    }

    public function getFilteredProfessionals(Request $request)
    {
        if ($request->ajax()) {
            $institution = InstitutionProfile::find(decrypt($request->id));
            $list = $institution->professionalProfiles->where('status', '<>', 0)->toQuery();
            
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
                    $list = $list->whereIn('profile_id', $profs);
                    
                }
            }
            
            $collection = array();
            $list = $list->get();
            //dd($list);
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
                    'institution' => $item->institution->id != 1 ? 'Lugar de consulta: ' . $item->institution->name : 'Independiente / Consultorio propio',
                    'coverages' => implode(" / ", $coverages),

                ];
                array_push($collection, $obj);
            }
            return response()->json(['status' => 'success', 'content' => $collection]);
        }
    }

}
