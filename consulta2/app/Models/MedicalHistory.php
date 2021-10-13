<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'medical_histories';

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
}
