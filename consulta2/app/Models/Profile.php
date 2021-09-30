<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'bornDate',
        'gender',
        'phone',
        'address'
    ];

    public function patientProfile() {
        return $this->hasOne(PatientProfile::class);
    }

    public function professionalProfile() {
        return $this->hasOne(ProfessionalProfile::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'profile_id');
    }
}
