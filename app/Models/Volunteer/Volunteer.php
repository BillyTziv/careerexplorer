<?php

namespace App\Models\Volunteer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Volunteer\Comment;
use App\Models\Volunteer\Task;
use App\Models\Team;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_to',
        'firstname',
        'lastname',
        'phone',
        'email',
        'status',
        'description',
        'expectations',
        'interests',
        'reason',
        'facebook',
        'instagram',
        'linkedin',
        'tiktok',
        'university',
        'department',
        'otherstudies',
        'deleted',
        'role',
        'hour_per_week',
        'previous_volunteering',
        'volunteering_details',
        'additional_info',
        'notes',
        'asana',
        'google_drive',
        'disapproved_reason',
        'start_date',
        'end_date',
        'date_of_birth',
        'city',
        'address',
        'cv',
        'current_company',
        'current_role',
        'hours_contributed',
        'onboarding_completed',
        'previous_volunteer_experience',
        'years_experience',
        'gender',
        'age',
        'career_status',
        'cv_consent',
        'team_id'
    ];

    protected $casts = [
        'onboarding_completed' => 'boolean',
    ];

    /**
     * Get the user that the volunteer is assigned to.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the role that the volunteer is assigned to.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\VolunteerRole', 'role');
    }

    /**
     * Get the comments for the volunteer.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the tasks for the volunteer.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the status for the volunteer.
     */
    public function status()
    {
        return $this->belongsTo(VolunteerStatus::class, 'status');
    }

    /**
     * Get the team that owns the volunteer.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
