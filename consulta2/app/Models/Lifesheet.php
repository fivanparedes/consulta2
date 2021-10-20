<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lifesheet extends Model
{
    use HasFactory;

    protected $table = 'lifesheets';

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
        return $this->hasOne(Coverage::class);
    }
}
