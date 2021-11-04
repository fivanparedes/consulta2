<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;

    protected $table = 'prices';

    protected $fillable = [
        'price',
        'copayment'
    ];

    public function coverage() {
        return $this->belongsTo(Coverage::class);
    }

    public function practice() {
        return $this->belongsTo(Practice::class);
    }
}