<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'cover_letter',
        'resume_path',
        'status',
    ];

    /**
     * Get the job that was applied to.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get the user who applied.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
