<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    use HasFactory;

    protected $table = 'practices';

    protected $fillable = [
        'name',
        'description',
        'maxtime'
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