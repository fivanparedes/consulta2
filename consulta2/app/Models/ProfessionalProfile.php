<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalProfile extends Model
{
    use HasFactory;
    
    protected $table = 'professional_profiles';
    
    protected $fillable = [
        'licensePlate',
        'field',
        'specialty'
    ];

    public function profile() {
        return $this->belongsTo(Profile::class);
    }
}
