<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserManagement\User;

class Answer extends Model
{
    use HasFactory;

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
