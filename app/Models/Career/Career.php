<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

/* Models */
use App\Models\Career\Interest;
use App\Models\Career\Skill;
use App\Models\Career\RiasecCode;
use App\Models\Career\Course;
use App\Models\Career\CareerResponsibility;

class Career extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'status',
        'like',
        'description',
        'deleted',
        'remember_token',
        'link',
        'interests' // add this field
    ];

    public function riasecCodes()
    {
        return $this->belongsToMany(RiasecCode::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function responsibilities()
    {
        return $this->hasMany(CareerResponsibility::class);
    }

    public function jobSectors()
    {
        return $this->belongsToMany(JobSector::class, 'career_job_sector');
    }
}
