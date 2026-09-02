<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'location',
        'profile_image',
        'status',
    ];

    /**
     * Get the job seeker profile for the user.
     */
    public function jobSeekerProfile()
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    /**
     * Get the employer profile for the user.
     */
    public function employer()
    {
        return $this->hasOne(Employer::class);
    }

    /**
     * Get the jobs posted by this employer.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    /**
     * Get the job applications submitted by this user.
     */
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is moderator
     */
    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    /**
     * Check if user is employer
     */
    public function isEmployer()
    {
        return $this->role === 'employer';
    }

    /**
     * Check if user is job seeker
     */
    public function isJobSeeker()
    {
        return $this->role === 'job_seeker';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
