<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the jobs for the category.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
