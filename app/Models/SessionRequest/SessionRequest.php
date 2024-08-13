<?php

namespace App\Models\SessionRequest;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionRequest extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee');
    }
}
