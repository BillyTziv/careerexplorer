<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Volunteer\Volunteer;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'volunteer_id',
        'task_name',
        'description',
        'due_date',
        'estimated_time',
        'points',
        'priority',
        'status_id',
        'team_id',
        'category_id'
    ];

    protected $dates = ['due_date'];

    /**
     * Get the volunteer that owns the task.
     */
    public function volunteer()
    {
        return $this->belongsTo(Volunteer::class);
    }

    /**
     * Get the status of the task.
     */
    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    /**
     * Get the priority of the task.
     */
    // public function priority()
    // {
    //     return $this->belongsTo(Priority::class);
    // }

    /**
     * Get the team that the task belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);  // Relationship to Team model
    }

    /**
     * Get the category of the task.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);  // Relationship to Category model
    }

    /**
     * Get the logs for the task.
     */
    public function taskLogs()
    {
        return $this->hasMany(TaskLog::class, 'task_id');
    }

    /**
     * Accessor to calculate actual time from task logs.
     *
     * @return int
     */
    public function getActualTimeAttribute()
    {
        return $this->taskLogs()->sum('duration');
    }
}
