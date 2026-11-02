# JobBoard — Laravel + Bootstrap Job Portal

Month 2 project (Web Development): a job board where **employers post jobs**, **job seekers search & apply with resumes**, get **job alerts**, and an **admin** manages listings and users.

## Features

| Role | What they can do |
|---|---|
| **Guest** | Browse & search jobs (keyword, location, category, type), view job details |
| **Job seeker** | Register, upload resume to profile, apply with resume + cover letter, track application status, create job alerts, get notifications |
| **Employer** | Register with company, post / edit / close / delete jobs, see applicants, download resumes, set status (pending → shortlisted / rejected / hired) |
| **Admin** | Dashboard with stats, manage users (block, change role, delete), manage all listings (close / reopen / delete) |

Extra: role-based middleware, private resume storage (only the applicant, the job's employer and admin can download), database + email notifications, Bootstrap 5 responsive UI, demo seed data.

## Tech stack
- Laravel 13 (PHP 8.3), Blade templates
- Bootstrap 5.3 + Bootstrap Icons (CDN — no npm needed)
- SQLite by default (MySQL optional)

## Run it locally

You need PHP 8.3+ and Composer ([Laragon](https://laragon.org) includes both).

```
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Then open **http://127.0.0.1:8000**. On macOS/Linux use `cp` instead of `copy`.

### Demo accounts (password for all: `password`)
These exist only after seeding, for local demos.

| Role | Email |
|---|---|
| Admin | admin@jobboard.test |
| Employer | employer@jobboard.test |
| Employer 2 | employer2@jobboard.test |
| Job seeker | seeker@jobboard.test |

### Job-alert emails
`MAIL_MAILER=log` (Laravel default) writes emails to `storage/logs/laravel.log`. For real emails, set SMTP details in `.env`.

## Demo flow
1. Login as **seeker** → Job Alerts → an alert already exists for "Laravel" in "Chennai".
2. Login as **employer** → Post a Job: "Senior Laravel Developer", Chennai → the success message says 1 seeker was notified.
3. Login as **seeker** → bell icon shows the alert → open the job → upload a PDF resume → Apply.
4. Login as **employer** → My Jobs → Applicants → download resume → change status to *Shortlisted*.
5. Login as **seeker** → notification shows the new status.
6. Login as **admin** → dashboard stats, block a user, close a listing.

## Database design
```
users (id, name, email, password, role, company_name, phone, resume_path, is_blocked)
  1 ── * job_listings (user_id, title, company_name, location, category, job_type,
                       salary_min, salary_max, description, requirements, deadline, status)
  1 ── * applications (job_listing_id, user_id, resume_path, cover_letter, status)
  1 ── * job_alerts   (user_id, keyword, location, category, job_type, is_active)
job_listings 1 ── * applications
```
