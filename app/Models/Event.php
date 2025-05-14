<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'category',
        'image',
        'user_id',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}