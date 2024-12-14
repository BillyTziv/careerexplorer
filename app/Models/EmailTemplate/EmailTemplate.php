<?php

namespace App\Models\EmailTemplate;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'body',
        'placeholders'
    ];

    /**
     * Get the task statuses associated with the email template.
     */
    public function taskStatuses()
    {
        return $this->hasMany(TaskStatus::class);
    }
}
