<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class NonWorkableDay extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use AuditingAuditable;

    protected $table = "non_workable_days";

    protected $fillable = [
        'concept',
        'from',
        'to'
    ];

    protected $auditInclude = [
        'concept',
        'from',
        'to'
    ];

    public $sortable = [
        'id',
        'concept',
        'from',
        'to'
    ];

    public function professionalProfile() {
        return $this->belongsTo(ProfessionalProfile::class);
    }
}
