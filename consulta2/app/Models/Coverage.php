<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    use HasFactory;

    protected $table = 'coverages';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'supported'
    ];

    public function lifesheet() {
        return $this->hasMany(Lifesheet::class);
    }

    public function practices() {
        return $this->belongsToMany(Practice::class);
    }
}