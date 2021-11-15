<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalProfile extends Model
{
    use HasFactory;
    
    protected $table = 'professional_profiles';
    
    protected $fillable = [
        'licensePlate',
        'field',
        'status'
    ];

    public function profile() {
        return $this->belongsTo(Profile::class);
    }

    public function calendarEvents() {
        return $this->hasMany(CalendarEvent::class);
    }

    public function institution() {
        return $this->belongsTo(InstitutionProfile::class);
    }

    public function businessHours() {
        return $this->belongsToMany(BusinessHour::class, 'hours_professionals', 'professional_profile_id', 'business_hour_id');
    }

    public function specialty() {
        return $this->belongsTo(Specialty::class);
    }

    public function coverages() {
        return $this->belongsToMany(Coverage::class, 'coverage_professionals', 'professional_id', 'coverage_id');
    }

    public function consultTypes() {
        return $this->hasMany(ConsultType::class);
    }
}