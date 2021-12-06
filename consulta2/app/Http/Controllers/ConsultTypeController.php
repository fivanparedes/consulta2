<?php

namespace App\Http\Controllers;

use App\Models\BusinessHour;
use App\Models\ConsultType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Input\Input;

class ConsultTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Auth::user()->profile->professionalProfile->consultTypes;
        return view('consult_types.index')->with(['types' => $types, 'professional' => Auth::user()->profile->professionalProfile]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $professional = Auth::user()->profile->professionalProfile;
        return view('consult_types.create')->with(['professional' => $professional]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profile = Auth::user()->profile->professionalProfile;
        //return dd($request->all());
        $av = '';
        if ($request->input('av-monday') == 'on') {
            $av .= '1;';
        }
        if ($request->input('av-tuesday') == 'on') {
            $av .= '2;';
        }
        if ($request->input('av-wednesday') == 'on') {
            $av .= '3;';
        }
        if ($request->input('av-thursday') == 'on') {
            $av .= '4;';
        }
        if ($request->input('av-friday') == 'on') {
            $av .= '5;';
        }
        if ($request->input('av-saturday') == 'on') {
            $av .= '6;';
        }
        if ($request->input('av-sunday') == 'on') {
            $av .= '7';
        }
        $consulttype = new ConsultType();

        $consulttype->name = $request->input('name');
        $consulttype->availability = $av;
        $consulttype->visible = $request->input('visible') == 'on' ? true : false;
        $consulttype->requires_auth = $request->input('requires_auth') == 'on' ? true : false;


        $consulttype->professionalProfile()->associate($profile);
        $consulttype->save();
        $consulttype->practices()->attach($request->selected_practices);
        $consulttype->businessHours()->attach($request->business_hours);
        $consulttype->save();
        return redirect('/consult_types');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function getCategorizedHours(Request $request)
    {
        if ($request->ajax()) {
            $list = null;
            switch ($request->choice) {
                case '1': //Cada 1 hora
                    $list = BusinessHour::where('time', 'like', '%:00:00')->get(['time'])->all();
                    break;
                case '2': //Cada media hora
                    $list = BusinessHour::where('time', 'like', '%:30%')->orWhere('time', 'like', '%:00:00')->get(['time'])->all();
                    break;
                case '3': //Cada 20 minutos
                    $list = BusinessHour::where('time', 'like', '%:00:00')->orWhere('time', 'like', '%:20:00')
                        ->orWhere('time', 'like', '%:40:00')->get(['time'])->all();
                    break;
                case '4': //Cada 10 minutos
                    $list = BusinessHour::where('time', 'like', '%:_0:00%')->get(['time'])->all();
                    break;
                case '5': //Cada 5 minutos
                    $list = BusinessHour::where('time', 'like', '%:_5:00')->orWhere('time', 'like', '%:_0:00')->get(['time'])->all();
                    break;
                case '6': //Todas
                    $list = BusinessHour::all();
                    break;
                default:
                    return response()->json([
                        'status' => 'error'
                    ]);
                    break;
            }
            return response()->json([
                'status' => 'success',
                'data' => $list
            ]);
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
        $consult = ConsultType::find(base64_decode(base64_decode($id)));
        $professional = Auth::user()->profile->professionalProfile;
        if ($consult->professionalProfile != $professional) {
            return abort(404);
        }
        $av = explode(';', $consult->availability);
        return view('consult_types.edit')->with([
            'consult' => $consult,
            'professional' => $professional,
            'av' => $av
        ]);
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
        $profile = Auth::user()->profile->professionalProfile;
        //return dd($request->all());
        $av = '';
        if ($request->input('av-monday') == 'on') {
            $av .= '1;';
        }
        if ($request->input('av-tuesday') == 'on') {
            $av .= '2;';
        }
        if ($request->input('av-wednesday') == 'on') {
            $av .= '3;';
        }
        if ($request->input('av-thursday') == 'on') {
            $av .= '4;';
        }
        if ($request->input('av-friday') == 'on') {
            $av .= '5;';
        }
        if ($request->input('av-saturday') == 'on') {
            $av .= '6;';
        }
        if ($request->input('av-sunday') == 'on') {
            $av .= '7';
        }
        $consulttype = ConsultType::find($id);

        $consulttype->name = $request->input('name');
        $consulttype->availability = $av;
        $consulttype->visible = $request->input('visible') == 'on' ? true : false;
        $consulttype->requires_auth = $request->input('requires_auth') == 'on' ? true : false;
        $consulttype->practices()->detach();
        $consulttype->practices()->attach($request->selected_practices);
        $consulttype->businessHours()->detach();
        $consulttype->businessHours()->attach($request->business_hours);
        $consulttype->save();
        return redirect('/consult_types');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $consult = ConsultType::find($id);
        $consult->destroy($id);
        return redirect('/consult_types');
    }
}
