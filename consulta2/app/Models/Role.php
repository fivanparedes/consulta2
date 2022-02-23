<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [ ];

    public $fillable = [
        'name',
        'display_name',
        'description'
    ];
    
}
