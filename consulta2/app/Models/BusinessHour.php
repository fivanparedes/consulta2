<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    use HasFactory;

    protected $table = 'business_hours';

    protected $fillable = ['time'];

    public function professionalProfiles() {
        return $this->belongsToMany(ProfessionalProfile::class, 'hours_professionals', 'business_hour_id', 'professional_profile_id');
    }

    public function consultTypes() {
        return $this->belongsToMany(ConsultType::class);
    }
}