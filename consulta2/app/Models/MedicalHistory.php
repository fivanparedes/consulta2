<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class MedicalHistory extends Model implements Auditable
{
    use HasFactory;

    use AuditingAuditable;

    protected $table = 'medical_histories';

    protected $auditInclude = [
        'indate',
        'psicological_history',
        'visitreason',
        'diagnosis',
    'clinical_history'    ];

    protected $fillable = [
        'indate',
        'psicological_history',
        'visitreason',
        'diagnosis',
        'clinical_history'
    ];

    public function patientProfile() {
        return $this->belongsTo(PatientProfile::class);
    }

    public function professionalProfile() {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function cites() {
        return $this->hasMany(Cite::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }

    public function treatments() {
        return $this->hasMany(Treatment::class);
    }
}
