<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@jobportal.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create Moderator User
        $moderator = User::create([
            'name' => 'Moderator User',
            'email' => 'moderator@jobportal.com',
            'password' => bcrypt('password'),
            'role' => 'moderator',
            'status' => 'active',
        ]);

        // Create Employer Users
        $employer1 = User::create([
            'name' => 'Tech Solutions Inc',
            'email' => 'employer1@jobportal.com',
            'password' => bcrypt('password'),
            'role' => 'employer',
            'phone' => '555-0001',
            'location' => 'San Francisco, CA',
            'status' => 'active',
        ]);

        $employer2 = User::create([
            'name' => 'Creative Agency',
            'email' => 'employer2@jobportal.com',
            'password' => bcrypt('password'),
            'role' => 'employer',
            'phone' => '555-0002',
            'location' => 'New York, NY',
            'status' => 'active',
        ]);

        // Create Employer Profiles (STORE IN VARIABLES!)
        $employerProfile1 = \App\Models\Employer::create([
            'user_id' => $employer1->id,
            'company_name' => 'Tech Solutions Inc',
            'company_description' => 'Leading technology solutions provider',
            'website' => 'https://techsolutions.com',
            'industry' => 'Technology',
        ]);

        $employerProfile2 = \App\Models\Employer::create([
            'user_id' => $employer2->id,
            'company_name' => 'Creative Agency',
            'company_description' => 'Full-service creative agency',
            'website' => 'https://creativeagency.com',
            'industry' => 'Marketing',
        ]);

        // Create Job Seeker Users
        $jobSeeker1 = User::create([
            'name' => 'John Doe',
            'email' => 'jobseeker1@jobportal.com',
            'password' => bcrypt('password'),
            'role' => 'job_seeker',
            'phone' => '555-1001',
            'location' => 'Los Angeles, CA',
            'status' => 'active',
        ]);

        $jobSeeker2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jobseeker2@jobportal.com',
            'password' => bcrypt('password'),
            'role' => 'job_seeker',
            'phone' => '555-1002',
            'location' => 'Chicago, IL',
            'status' => 'active',
        ]);

        // Create Job Seeker Profiles
        \App\Models\JobSeekerProfile::create([
            'user_id' => $jobSeeker1->id,
            'skills' => 'Laravel, PHP, MySQL, JavaScript, Vue.js',
            'experience' => '5 years of web development experience',
            'education' => 'BS Computer Science',
        ]);

        \App\Models\JobSeekerProfile::create([
            'user_id' => $jobSeeker2->id,
            'skills' => 'Digital Marketing, SEO, Content Strategy, Social Media',
            'experience' => '3 years of marketing experience',
            'education' => 'BA Marketing',
        ]);

        // Create Categories
        $categories = [
            ['name' => 'IT & Technology', 'slug' => 'it-technology'],
            ['name' => 'Marketing', 'slug' => 'marketing'],
            ['name' => 'Finance', 'slug' => 'finance'],
            ['name' => 'Design', 'slug' => 'design'],
            ['name' => 'Sales', 'slug' => 'sales'],
            ['name' => 'Human Resources', 'slug' => 'human-resources'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        // Create Jobs (USE EMPLOYER PROFILE IDs!)
        $itCategory = \App\Models\Category::where('slug', 'it-technology')->first();
        $marketingCategory = \App\Models\Category::where('slug', 'marketing')->first();

        \App\Models\Job::create([
            'employer_id' => $employerProfile1->id, // USE EMPLOYER PROFILE ID!
            'category_id' => $itCategory->id,
            'title' => 'Senior Laravel Developer',
            'description' => 'We are looking for an experienced Laravel developer to join our team. You will work on exciting projects and collaborate with talented developers.',
            'requirements' => '- 5+ years PHP/Laravel experience
- Strong MySQL knowledge
- Experience with Vue.js or React
- Git proficiency',
            'salary_min' => 80000,
            'salary_max' => 120000,
            'location' => 'San Francisco, CA (Remote OK)',
            'job_type' => 'full-time',
            'status' => 'approved',
            'deadline' => now()->addDays(30),
        ]);

        \App\Models\Job::create([
            'employer_id' => $employerProfile2->id, // USE EMPLOYER PROFILE ID!
            'category_id' => $marketingCategory->id,
            'title' => 'Digital Marketing Manager',
            'description' => 'Join our creative team and lead innovative marketing campaigns for top brands.',
            'requirements' => '- 3+ years digital marketing experience
- SEO/SEM expertise
- Social media management
- Analytics proficiency',
            'salary_min' => 60000,
            'salary_max' => 90000,
            'location' => 'New York, NY',
            'job_type' => 'full-time',
            'status' => 'approved',
            'deadline' => now()->addDays(30),
        ]);

        \App\Models\Job::create([
            'employer_id' => $employerProfile1->id, // USE EMPLOYER PROFILE ID!
            'category_id' => $itCategory->id,
            'title' => 'Frontend Developer',
            'description' => 'We need a talented frontend developer to create amazing user experiences.',
            'requirements' => '- Strong JavaScript/TypeScript
- React or Vue.js experience
- CSS/Tailwind expertise
- Responsive design skills',
            'salary_min' => 70000,
            'salary_max' => 100000,
            'location' => 'Remote',
            'job_type' => 'full-time',
            'status' => 'pending',
            'deadline' => now()->addDays(30),
        ]);

        echo "✅ Database seeded successfully!\n";
        echo "📧 Admin: admin@jobportal.com / password\n";
        echo "📧 Moderator: moderator@jobportal.com / password\n";
        echo "📧 Employer: employer1@jobportal.com / password\n";
        echo "📧 Job Seeker: jobseeker1@jobportal.com / password\n";
    }
}
