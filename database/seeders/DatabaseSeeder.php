<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Demo data. Demo accounts use the password: password
     * The admin password comes from ADMIN_PASSWORD on the live site.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Site Admin',
            'email' => 'admin@jobboard.test',
            'password' => env('ADMIN_PASSWORD', 'password'),
            'role' => 'admin',
        ]);

        $employer = User::create([
            'name' => 'Priya HR',
            'email' => 'employer@jobboard.test',
            'password' => 'password',
            'role' => 'employer',
            'company_name' => 'TechNova Solutions',
            'phone' => '9876543210',
        ]);

        $employer2 = User::create([
            'name' => 'Karthik Manager',
            'email' => 'employer2@jobboard.test',
            'password' => 'password',
            'role' => 'employer',
            'company_name' => 'BrightPixel Studio',
        ]);

        $seeker = User::create([
            'name' => 'Arun Kumar',
            'email' => 'seeker@jobboard.test',
            'password' => 'password',
            'role' => 'seeker',
            'phone' => '9123456780',
        ]);

        $seeker->jobAlerts()->create(['keyword' => 'Laravel', 'location' => 'Chennai']);

        $jobs = [
            [$employer, 'Junior Laravel Developer', 'Chennai', 'IT & Software', 'Full-time', 300000, 450000],
            [$employer, 'Frontend Developer (Bootstrap / JS)', 'Bengaluru', 'IT & Software', 'Full-time', 350000, 600000],
            [$employer, 'PHP Developer Intern', 'Coimbatore', 'IT & Software', 'Internship', 10000, 15000],
            [$employer, 'QA Tester', 'Chennai', 'IT & Software', 'Contract', 250000, 350000],
            [$employer2, 'UI/UX Designer', 'Remote', 'Design', 'Remote', 400000, 700000],
            [$employer2, 'Digital Marketing Executive', 'Madurai', 'Marketing', 'Full-time', 200000, 300000],
            [$employer2, 'Graphic Design Intern', 'Chennai', 'Design', 'Internship', 8000, 12000],
            [$employer2, 'Customer Support Associate', 'Hyderabad', 'Customer Support', 'Part-time', null, null],
        ];

        foreach ($jobs as [$owner, $title, $location, $category, $type, $min, $max]) {
            $owner->jobListings()->create([
                'title' => $title,
                'company_name' => $owner->company_name,
                'location' => $location,
                'category' => $category,
                'job_type' => $type,
                'salary_min' => $min,
                'salary_max' => $max,
                'description' => "We are looking for a motivated {$title} to join our growing team at {$owner->company_name}. "
                    .'You will work closely with senior team members, take ownership of real tasks, and learn fast in a friendly environment.',
                'requirements' => "Good communication skills\nWillingness to learn\nRelevant degree or equivalent experience\nTeam player",
                'deadline' => now()->addDays(rand(10, 45)),
                'status' => 'open',
            ]);
        }
    }
}
