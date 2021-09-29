<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {  
            $data = CalendarEvent::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->get(['id', 'title', 'start', 'end']);
            return response()->json($data);
        }
        return view('calendar');
    }
 

    public function manageEvents(Request $request)
    {
        switch ($request->type) {
           case 'create':
              $calendarEvent = CalendarEvent::create([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
              ]);
 
              return response()->json($calendarEvent);
             break;
  
           case 'update':
              $calendarEvent = CalendarEvent::find($request->id)->update([
                  'title' => $request->title,
                  'start' => $request->start,
                  'end' => $request->end,
                  'patient' => random_int(1, 10000)
              ]);
 
              return response()->json($calendarEvent);
             break;
  
           case 'delete':
              $calendarEvent = CalendarEvent::find($request->id)->delete();
  
              return response()->json($calendarEvent);
             break;
             
           default:
             break;
        }
    }
}
