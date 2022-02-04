<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Akaunting\Sortable\Traits\Sortable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Nomenclature extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use AuditingAuditable;

    protected $table = 'nomenclatures';

    protected $fillable = [
        'code',
        'description'
    ];

    protected $auditInclude = [
        'code',
        'description'
    ];

    public $sortable = [
        'id',
        'code',
        'description',
        'specialty'
    ];
    public function practices() {
        return $this->hasMany(Practice::class);
    }

    public function specialty() {
        return $this->belongsTo(Specialty::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($nom) {
            $nom->practices()->delete();
            $nom->specialty()->dissociate();
        });
    }
}