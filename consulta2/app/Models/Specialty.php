<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Specialty extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use AuditingAuditable;

    protected $table = 'specialties';

    protected $fillable = [
        'name',
        'displayname'
    ];

    protected $auditInclude = [
        'name',
        'displayname'
    ];

    public $sortable = [
        'id',
        'name',
        'displayname'
    ];

    public function professionalProfiles() {
        return $this->hasMany(ProfessionalProfile::class);
    }

    public function nomenclatures() {
        return $this->hasMany(Nomenclature::class);
    }
}