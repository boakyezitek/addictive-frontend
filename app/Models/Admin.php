<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function events()
    {
        return $this->morphMany(Event::class, 'owner');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Determines if the User is a Super admin
     * @return null
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }
}
