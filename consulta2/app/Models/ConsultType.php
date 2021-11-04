<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultType extends Model
{
    use HasFactory;

    protected $table = 'consult_types';

    protected $fillable = [
        'name'
    ];

    public function practices() {
        return $this->hasMany(Practice::class);
    }

    public function calendarEvent() {
        return $this->hasMany(CalendarEvent::class);
    }

    public function businessHours() {
        return $this->belongsToMany(BusinessHour::class);
    }
}