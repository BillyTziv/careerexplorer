<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // Add other relevant fields
    ];

    /**
     * Get the volunteers with this role.
     */
    public function volunteers()
    {
        return $this->hasMany(Volunteer::class, 'role');
    }
}
