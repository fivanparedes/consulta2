<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'cities';

    protected $fillable = [
        'name'
    ];

    public $sortable = [
        'id',
        'name',
        'province'
    ];

    public function profiles() {
        return $this->hasMany(Profile::class);
    }

    public function institutions() {
        return $this->hasMany(Institution::class);
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

    public function coverages() {
        return $this->hasMany(Coverage::class);
    }
}
