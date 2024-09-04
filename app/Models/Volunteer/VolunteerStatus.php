<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VolunteerStatus extends Model
{
    use HasFactory;
    
    // Define the fillable attributes
    protected $fillable = [
        'name',
        'description',
        'hex_color',
        'is_default',
        'is_active',
        'email_template_id',
    ];
}
