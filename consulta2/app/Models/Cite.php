<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Cite extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'cites';

    protected $fillable = [
        'assisted',
        'covered',
        'paid'
    ];

    public $sortable = [
        'id',
        'assisted',
        'covered',
        'paid',
        'calendarEvent',
        'practice'
    ];

    public function calendarEventSortable($query, $direction) {
        return $query->join('calendar_events', 'calendar_events.id', '=', 'cites.calendar_event_id')
        ->orderBy('calendar_events.start', $direction)
            ->select('calendar_events.*');
    }
    public function calendarEvent() {
        return $this->belongsTo(CalendarEvent::class);
    }

    public function practice() {
        return $this->belongsTo(Practice::class);
    }

    public function medicalHistory() {
        return $this->belongsTo(MedicalHistory::class);
    }

    public function treatment() {
        return $this->belongsTo(Treatment::class);
    }

    public function document() {
        return $this->hasOne(Document::class);
    }
}