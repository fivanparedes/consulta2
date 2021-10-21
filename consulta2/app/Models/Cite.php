<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cite extends Model
{
    use HasFactory;

    protected $table = 'cites';

    protected $fillable = [
        'assisted',
        'isVirtual',
        'covered',
        'paid'
    ];

    public function calendarEvent() {
        return $this->belongsTo(CalendarEvent::class);
    }

    public function practice() {
        return $this->belongsTo(Practice::class);
    }
}