<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $table = 'reminders';

    protected $fillable = [
        'sent',
        'answered'
    ];

    public function calendarEvent() {
        return $this->belongsTo(CalendarEvent::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}