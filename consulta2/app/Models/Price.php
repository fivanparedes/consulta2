<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    use Sortable;

    protected $table = 'prices';

    protected $fillable = [
        'price',
        'copayment'
    ];

    public $sortable = [
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