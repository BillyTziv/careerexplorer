<?php

namespace App\Models\SessionRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRequestStatus extends Model
{
    use HasFactory;

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'description',
        'hex_color',
        'is_default',
        'is_active',
        'accept',
        'reject',
        'complete',
        'email_template_id',
    ];
}
