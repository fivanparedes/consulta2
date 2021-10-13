<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    use HasFactory;

    protected $table = 'coverage';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'supported'
    ];

    public function lifesheet() {
        return $this->belongsTo(Lifesheet::class);
    }
}
