<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalProfile extends Model
{
    use HasFactory;
    use Sortable;
    
    protected $table = 'professional_profiles';
    
    protected $fillable = [
        'licensePlate',
        'field',
        'status'
    ];

    protected $sortable = [
        'id',
        'profile',
        'user',
        'licensePlate',
        'status',
        'created_at',
        'institution',
        'city',
        'specialty'
    ];

    public function profile() {
        return $this->belongsTo(Profile::class);
    }

    public function profileSortable($query, $direction)
    {
        return $query->join('profiles', 'profile.id', '=', 'professional_profiles.profile_id')
        ->orderBy('profiles.id', $direction)
            ->select('profiles.*');
    }

    public function userSortable($query, $direction) {
        return $query->join('profiles', 'profile.id', '=', 'professional_profiles.profile_id')
            ->join('users', 'users.id', '=', 'profiles.id')
            ->orderBy('users.dni', $direction)
            ->select('users.dni', 'users.lastname');
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

    public function medicalHistories() {
        return $this->hasMany(MedicalHistory::class);
    }
}