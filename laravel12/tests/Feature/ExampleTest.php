<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Employer;
use App\Models\Job;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_employer_dashboard_has_paginated_jobs(): void
    {
        $user = User::factory()->create([
            'role' => 'employer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $employer = Employer::create([
            'user_id' => $user->id,
            'company_name' => 'Acme Studio',
            'company_description' => 'Creative hiring studio.',
            'industry' => 'Technology',
        ]);

        $category = Category::create([
            'name' => 'Engineering',
            'slug' => 'engineering',
        ]);

        for ($i = 1; $i <= 11; $i++) {
            Job::create([
                'employer_id' => $employer->id,
                'category_id' => $category->id,
                'title' => 'Job ' . $i,
                'description' => 'A test role',
                'location' => 'Remote',
                'job_type' => 'full-time',
                'status' => 'approved',
            ]);
        }

        $response = $this->actingAs($user)->get('/employer/dashboard?page=2');

        $response->assertOk();
        $response->assertSee('Job 5');
        $response->assertDontSee('Job 11');
    }

    public function test_employer_cannot_submit_duplicate_job(): void
    {
        $user = User::factory()->create(['role' => 'employer', 'status' => 'active']);
        $employer = Employer::create([
            'user_id' => $user->id,
            'company_name' => 'Acme Studio',
            'company_description' => 'Creative hiring studio.',
            'industry' => 'Technology',
        ]);
        $category = Category::create(['name' => 'Engineering', 'slug' => 'engineering']);
        $job = [
            'title' => 'Product Designer',
            'description' => 'Design useful products.',
            'location' => 'Remote',
            'job_type' => 'full-time',
            'category_id' => $category->id,
        ];

        Job::create($job + ['employer_id' => $employer->id, 'status' => 'pending']);

        $response = $this->actingAs($user)->post('/employer/jobs', $job);

        $response->assertSessionHasErrors('title');
        $this->assertSame(1, Job::where('employer_id', $employer->id)->count());
    }
}
