<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $table = 'calendar_events';

    protected $fillable = [
        'title',
        'start',
        'end',
    ];

    public function patientProfile() {
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
}