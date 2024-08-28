<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CareerResponsibility extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'career_id'];
}
