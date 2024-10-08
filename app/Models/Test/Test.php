<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test\Question;

class Test extends Model
{
    use HasFactory;

    public function questions() {
        return $this->hasMany(Question::class);
    }
}
