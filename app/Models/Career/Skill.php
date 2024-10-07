<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    
    public function riasecCodes()
    {
        return $this->belongsToMany(RiasecCode::class);
    }
}
