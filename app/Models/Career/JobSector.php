<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSector extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'icon'];

    public function careers()
    {
        return $this->belongsToMany(Career::class, 'career_job_sector');
    }
}
