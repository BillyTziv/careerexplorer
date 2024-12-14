<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id',
        'start_time',
        'end_time',
        'duration',
    ];

    /**
     * Get the task that owns the log.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
