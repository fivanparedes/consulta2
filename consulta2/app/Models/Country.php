<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Country extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use Sortable;
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'code'
    ];

    protected $auditInclude = [
        'name',
        'code'
    ];

    public $sortable = [
        'id',
        'name',
        'code'
    ];

    public function provinces() {
        return $this->hasMany(Province::class);
    }
}
