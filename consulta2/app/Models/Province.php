<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Province extends Model implements Auditable
{
    use HasFactory;
    use AuditingAuditable;
    use Sortable;

    protected $table = 'provinces';

    protected $fillable = [
        'name'
    ];

    protected $auditInclude = [
        'name'
    ];

    public $sortable = [
        'id',
        'name',
        'country'
    ];

    public function cities() {
        return $this->hasMany(City::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }
}
