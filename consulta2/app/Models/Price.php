<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

class Price extends Model implements Auditable
{
    use HasFactory;
    use Sortable;
    use AuditingAuditable;

    protected $table = 'prices';

    protected $fillable = [
        'price',
        'copayment'
    ];

    protected $auditInclude = [
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