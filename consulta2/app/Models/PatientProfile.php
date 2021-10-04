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
}
