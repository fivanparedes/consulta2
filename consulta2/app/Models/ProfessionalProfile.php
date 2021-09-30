<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'licensePlate',
        'field',
        'specialty'
    ];

    public function parentProfile() {
        return $this->belongsTo(Profile::class, 'parentprofile_id', 'id');
    }
}
