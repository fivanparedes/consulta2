<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Treatment extends Model implements ContractsAuditable
{
    use HasFactory;
    use Sortable;
    use Auditable;

    protected $table = 'treatments';

    protected $fillable = [
        'name',
        'description',
        'times_per_month',
        'preferred_time',
        'preferred_days',
        'start',
        'end'
    ];

    public $sortable =
    [
        'name',
        'description',
        'patient',
        'times_per_month',
        'start',
        'end'
    ];

    public function patientSortable($query, $direction) {
        return $query->join('profiles', 'profile.id', '=', 'professional_profiles.profile_id')
        ->orderBy('profiles.id', $direction)
            ->select('profiles.*');
    }

    public function medicalHistory() {
        return $this->belongsTo(MedicalHistory::class);
    }

    public function cites() {
        return $this->hasMany(Cite::class);
    }
}
