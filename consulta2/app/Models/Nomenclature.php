<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use HasFactory;

    protected $table = 'nomenclatures';

    protected $fillable = [
        'code',
        'description'
    ];

    public function practices() {
        return $this->hasMany(Practice::class);
    }

    public function specialty() {
        return $this->belongsTo(Specialty::class);
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($nom) {
            $nom->practices()->delete();
            $nom->specialty()->dissociate();
        });
    }
}