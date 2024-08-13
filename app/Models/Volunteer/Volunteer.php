<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'disapproved_reason'];

    protected $casts = [
        'onboarding_completed' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\VolunteerRole', 'role');
    }
}
