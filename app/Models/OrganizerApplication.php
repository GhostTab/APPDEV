<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerApplication extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'experience',
        'status',
        'admin_notes'
    ];

    // Add an accessor for full name if needed
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}