<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_logo',
        'company_description',
        'website',
        'industry',
    ];

    /**
     * Get the user that owns the employer profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the jobs posted by this employer.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id', 'id');
    }
}
