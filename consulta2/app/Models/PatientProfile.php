<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class PatientProfile extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use AuditingAuditable;

    protected $table = 'patient_profiles';

    protected $fillable = [
        'bornPlace',
        'familyGroup',
        'familyPhone',
        'civilState',
        'scholarity',
        'occupation'
    ];

    protected $auditInclude = [
        'bornPlace',
        'familyGroup',
        'familyPhone',
        'civilState',
        'scholarity',
        'occupation'
    ];

    public $sortable = [
        'bornPlace',
        'familyGroup',
        'familyPhone',
        'civilState',
        'scholarity',
        'occupation',
        'coverage'
    ];

    public function getFullName() {
        return (string)$this->profile->user->lastname . ' ' . $this->profile->user->name;
    }

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