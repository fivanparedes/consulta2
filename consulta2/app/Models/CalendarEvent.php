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
        return $this->belongsToMany(PatientProfile::class);
    }

    public function professionalProfile() {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function cite() {
        return $this->hasOne(Cite::class);
    }

    public function reminder() {
        return $this->hasMany(Reminder::class);
    }
}
