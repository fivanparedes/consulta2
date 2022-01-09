<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'specialties';

    protected $fillable = [
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