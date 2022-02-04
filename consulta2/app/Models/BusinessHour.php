<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessHour extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'business_hours';

    protected $fillable = ['time'];

    protected $auditInclude = [
        'time',
    ];

    public function professionalProfiles() {
        return $this->belongsToMany(ProfessionalProfile::class, 'hours_professionals', 'business_hour_id', 'professional_profile_id');
    }

    public function consultTypes() {
        return $this->belongsToMany(ConsultType::class);
    }
}