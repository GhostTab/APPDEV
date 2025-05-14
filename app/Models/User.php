<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'profile_image',
    ];

    // Add this accessor to get full name
    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role_id' => 'integer',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    public function organizerApplication()
    {
        return $this->hasOne(OrganizerApplication::class);
    }
}
