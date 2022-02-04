<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ConsultType extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'consult_types';

    protected $fillable = [
        'name',
        'availability',
        'visible',
        'requires_auth'
    ];

    protected $auditInclude = [
        'name',
        'availability',
        'visible',
        'requires_auth'
    ];

    public function practices() {
        return $this->belongsToMany(Practice::class);
    }

    public function calendarEvent() {
        return $this->hasMany(CalendarEvent::class);
    }

    public function businessHours() {
        return $this->belongsToMany(BusinessHour::class);
    }

    public function professionalProfile() {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($consult) {
            $consult->practices()->detach();
            $consult->calendarEvent()->delete();
            $consult->businessHours()->detach();
            $consult->professionalProfile()->dissociate();
        });
    }

}