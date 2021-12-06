<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use HasFactory;

    protected $table = 'patient_profiles';

    protected $fillable = [
        'bornPlace',
        'familyGroup',
        'familyPhone',
        'civilState',
        'scholarity',
        'occupation'
    ];

    public function profile() {
        return $this->belongsTo(Profile::class);
    }

    public function calendarEvents() {
        return $this->belongsToMany(CalendarEvent::class, 'calendar_event_patient', 'patient_id', 'calendar_event_id');
    }

    public function lifesheet() {
        return $this->hasOne(Lifesheet::class);
    }
    
    public function medicalHistories() {
        return $this->hasMany(MedicalHistory::class);
    }
}