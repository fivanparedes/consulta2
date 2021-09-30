<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'bornPlace',
        'familyGroup',
        'familyPhone',
        'civilState',
        'scholarity',
        'occupation'
    ];

    public function parentProfile() {
        return $this->belongsTo(Profile::class, 'parentprofile_id', 'id');
    }
}
