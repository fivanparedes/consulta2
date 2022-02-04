<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Lifesheet extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'lifesheets';

    protected $auditInclude = [
        'diseases',
        'surgeries',
        'medication',
        'allergies',
        'smokes',
        'drinks',
        'exercises',
        'hceu'
    ];

    protected $fillable = [
        'diseases',
        'surgeries',
        'medication',
        'allergies',
        'smokes',
        'drinks',
        'exercises',
        'hceu'
    ];

    public function patientProfile() {
        return $this->belongsTo(PatientProfile::class);
    }

    public function coverage() {
        return $this->belongsTo(Coverage::class);
    }
}