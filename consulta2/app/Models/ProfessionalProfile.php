<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class ProfessionalProfile extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use AuditingAuditable;
    
    protected $table = 'professional_profiles';
    
    protected $fillable = [
        'licensePlate',
        'field',
        'status'
    ];

    protected $auditInclude = [
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
        return $this->belongsTo(InstitutionProfile::class, 'institution_id');
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

    public function nonWorkableDays() {
        return $this->hasMany(NonWorkableDay::class);
    }

    public function getFullName()
    {
        return (string)$this->profile->user->lastname . ' ' . $this->profile->user->name;
    }
}