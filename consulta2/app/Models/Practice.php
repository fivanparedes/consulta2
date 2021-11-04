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

    public function coverages() {
        return $this->belongsToMany(Coverage::class);
    }

    public function cites() {
        return $this->hasMany(Cite::class);
    }

    public function nomenclature() {
        return $this->belongsTo(Nomenclature::class);
    }
}