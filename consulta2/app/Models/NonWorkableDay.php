<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonWorkableDay extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = "non_workable_days";

    protected $fillable = [
        'concept',
        'from',
        'to'
    ];

    public $sortable = [
        'id',
        'concept',
        'from',
        'to'
    ];

    public function professionalProfile() {
        return $this->belongsTo(ProfessionalProfile::class);
    }
}
