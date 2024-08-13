<?php

namespace App\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course\Company;

class Course extends Model
{
    use HasFactory;
    protected $appends = ['company_name'];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getCompanyNameAttribute()
    {
        // Check if the course is loaded with a company to avoid null errors
        if ($this->relationLoaded('company') && $this->company) {
            return $this->company->name;
        }

        return null;
    }
}
