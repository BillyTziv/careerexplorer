<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiasecCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
    ];

    public function careers()
    {
        return $this->belongsToMany(Career::class);
    }
}
