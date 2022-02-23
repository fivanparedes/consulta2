<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Practice extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use AuditingAuditable;

    protected $table = 'practices';

    protected $fillable = [
        'name',
        'description',
        'maxtime'
    ];

    protected $auditInclude = [
        'name',
        'description',
        'maxtime'
    ];

    public $sortable = [
        'id',
        'name',
        'description',
        'maxtime',
        'coverage',
        'nomenclature',
        'price',
    ];
    public function coverage() {
        return $this->belongsTo(Coverage::class);
    }

    public function cites() {
        return $this->hasMany(Cite::class);
    }

    public function nomenclature() {
        return $this->belongsTo(Nomenclature::class);
    }

    public function consultTypes() {
        return $this->belongsToMany(ConsultType::class);
    }

    public function price() {
        return $this->hasOne(Price::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function ($practice){
            $practice->cites()->delete();
            $practice->coverage()->dissociate();
            $practice->nomenclature()->dissociate();
            $practice->consultTypes()->detach();
            $practice->price()->delete();
        });
    }
}