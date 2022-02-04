<?php

namespace App\Models;

use Akaunting\Sortable\Traits\Sortable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'dni',
        'name',
        'lastname',
        'email',
        'pfp',
        'password',
    ];

    public $sortable = [
        'id',
        'name',
        'lastname',
        'dni'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function reminders() {
        return $this->hasMany(Reminder::class);
    }

    public function institutionProfile() {
        return $this->hasOne(InstitutionProfile::class);
    }
}