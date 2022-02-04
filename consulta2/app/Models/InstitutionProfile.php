<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InstitutionProfile extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'institution_profiles';

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone'
    ];

    protected $auditInclude = [
        'name',
        'description',
        'address',
        'phone'
    ];

    public $sortable = [
        'id',
        'name',
        'description',
        'address',
        'phone'
    ];

    public function professionalProfiles() {
        return $this->hasMany(ProfessionalProfile::class, 'institution_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

}
