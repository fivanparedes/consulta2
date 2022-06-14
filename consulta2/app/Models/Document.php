<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public function medicalHistory() {
        return $this->belongsTo(MedicalHistory::class);
    }

    public function cite() {
        return $this->belongsTo(Cite::class);
    }
}
