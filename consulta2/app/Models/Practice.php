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
        'nomenclature',
        'description',
        'price',
        'copayment'
    ];

    public function coverages() {
        return $this->hasMany(Coverage::class);
    }

    public function cites() {
        return $this->hasMany(Cite::class);
    }
}
