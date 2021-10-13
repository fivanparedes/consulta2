<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $table = 'institutions';

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone'
    ];

    public function professionalProfiles() {
        return $this->hasMany(ProfessionalProfile::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

}
