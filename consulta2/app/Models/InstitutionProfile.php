<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionProfile extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'institution_profiles';

    protected $fillable = [
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
