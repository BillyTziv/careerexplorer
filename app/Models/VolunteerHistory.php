<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Volunteer\Volunteer;
use App\Models\UserManagement\User;

class VolunteerHistory extends Model
{
    use HasFactory;

    protected $fillable = ['volunteer_id', 'user_id', 'action', 'description'];

    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
