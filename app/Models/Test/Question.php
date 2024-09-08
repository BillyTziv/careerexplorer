<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test\Test;

class Question extends Model
{
    use HasFactory;

    public function tests() {
        return $this->belongsTo(Test::class);
    }
}
