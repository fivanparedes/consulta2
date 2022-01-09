<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title',
        'start',
        'end',
        'approved',
        'confirmed',
        'isVirtual'
    ];

    public $sortable = [
        'id',
        'title',
        'start',
        'approved',
        'confirmed',
        'isVirtual',
        'cite',
        'consultType',
    ];

    public function patientProfiles() {
        return $this->belongsToMany(PatientProfile::class, 'calendar_event_patient', 'calendar_event_id', 'patient_id');
    }

    public function professionalProfile() {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function cite() {
        return $this->hasOne(Cite::class);
    }

    public function consultType() {
        return $this->belongsTo(ConsultType::class);
    }

    public function reminder() {
        return $this->hasMany(Reminder::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($event) {
            $event->cite()->delete();
            $event->consultType()->dissociate();
            $event->professionalProfile()->dissociate();
            $event->patientProfiles()->detach();
            $event->reminder()->delete();
        });
    }
}