<?php

namespace App\Models\Career;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserManagement\Role;

class CareerRequest extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'keyword',
        'email',
        'status'
    ];

    public static function store($data) {
        return CareerRequest::create([
            'keyword' => $data['keyword'],
            'email' => $data['email'],
            'status' => $data['status'] ?? '0' // set a default value for the status column if it is not provided
        ]);
    }
}
