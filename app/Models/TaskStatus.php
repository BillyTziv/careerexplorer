<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'hex_color',
        'email_template_id',
        'is_default',
    ];

    /**
     * Get the email template associated with the task status.
     */
    public function emailTemplate()
    {
        return $this->belongsTo(EmailTemplate::class);
    }

    /**
     * Get the tasks associated with the status.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'status_id');
    }
}
