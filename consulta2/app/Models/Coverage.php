<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    use HasFactory;

    protected $table = 'coverages';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'supported'
    ];

    public function lifesheet() {
        return $this->hasMany(Lifesheet::class);
    }

    public function practices() {
        return $this->hasMany(Practice::class);
    }

    public function prices() {
        return $this->hasMany(Price::class);
    }

    public function professionalProfiles() {
        return $this->belongsToMany(ProfessionalProfile::class, 'coverage_professionals', 'coverage_id', 'professional_id');
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($cov) {
            $cov->practices()->delete();
            $cov->lifesheet()->delete();
            $cov->prices()->delete();
            $cov->professionalProfiles()->detach();
            $cov->city()->dissociate();
        });
    }
}