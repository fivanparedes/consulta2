<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Reminder extends Model implements Auditable
{
    use HasFactory;
    use AuditingAuditable;

    protected $table = 'reminders';

    protected $fillable = [
        'sent',
        'answered'
    ];

    protected $auditInclude = [
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