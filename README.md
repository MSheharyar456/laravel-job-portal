# Job Portal

A full-stack, role-based job portal built with Laravel. It provides a complete hiring workflow: employers publish jobs, moderators review them, job seekers apply with resumes, and administrators manage the platform.

## Highlights

- Role-based access for job seekers, employers, moderators, and administrators
- Secure registration, login, logout, email verification, password reset, and account-status checks
- Public job discovery with search, category, location, and job-type filters
- Employer profiles, company-logo uploads, job creation/editing, and application management
- Job seeker profiles, resume uploads, application submission, and application tracking
- Moderator approval and rejection workflow for job listings
- Administrator dashboards for users, jobs, categories, roles, and account status
- AJAX-powered search, filtering, pagination, and moderation actions for a responsive interface
- REST API authentication using Laravel Sanctum

## Technology Stack

| Technology | Version / Details |
| --- | --- |
| PHP | 8.2.12 used during development; PHP `^8.2` required |
| Laravel | 12.69.1 |
| Composer | 2.9.2 |
| MySQL | Active local database driver |
| Eloquent ORM | Included with Laravel 12 |
| Laravel Sanctum | `^4.3` for API token authentication |
| Blade | Laravel server-rendered templates |
| JavaScript | ES modules with Axios `^1.11.0` |
| Tailwind CSS | `^4.0.0` |
| Vite | `^7.0.7` |
| Node.js / npm | 24.19.0 / 11.7.0 used during development |
| Bootstrap Icons and Google Fonts | Used by the interface |

## How It Works

1. Visitors browse approved jobs and filter them by keyword, category, location, or job type.
2. A user registers as either a job seeker or an employer. The app automatically creates the appropriate profile.
3. Employers complete their company profile and submit jobs. New jobs start with a `pending` status.
4. Moderators review pending jobs and approve or reject them. Only approved jobs are visible on the public job board.
5. Job seekers complete their profile, upload a CV/resume, and apply to approved jobs. Duplicate applications are prevented.
6. Employers view applications for their own jobs and update the status: pending, reviewed, shortlisted, accepted, or rejected.
7. Administrators oversee users, account status, roles, job listings, and categories.

## Project Structure

```text
tst/
├── README.md
├── DEPLOYMENT_INSTRUCTIONS.md
└── laravel12/                 # Laravel application root
    ├── app/                   # Controllers, models, middleware
    ├── database/              # Migrations and seeders
    ├── public/                # Web server document root
    ├── resources/             # Blade templates, CSS, and JavaScript
    ├── routes/                # Web and API routes
    ├── composer.json
    └── package.json
```

## Requirements

- PHP 8.2 or later
- Composer 2
- MySQL 8+ (or another Laravel-supported database configured in `.env`)
- Node.js and npm (required to build frontend assets)
- PHP extensions required by Laravel and your chosen database driver, including `pdo_mysql` for MySQL

## Local Installation

Run all application commands from the Laravel directory:

```bash
cd laravel12
```

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Create your local environment file:

   ```bash
   cp .env.example .env
   ```

   On Windows PowerShell, use:

   ```powershell
   Copy-Item .env.example .env
   ```

3. Generate an application key:

   ```bash
   php artisan key:generate
   ```

4. Configure your MySQL connection in `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=job_portal
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

   Create the `job_portal` database first, or use your preferred database name.

5. Run the migrations and load sample data:

   ```bash
   php artisan migrate --seed
   ```

6. Install frontend dependencies and build assets:

   ```bash
   npm install
   npm run build
   ```

7. Make public resume and company-logo uploads accessible:

   ```bash
   php artisan storage:link
   ```

8. Start the application:

   ```bash
   php artisan serve
   ```

   Open [http://localhost:8000](http://localhost:8000).

For frontend development with automatic rebuilding, run this in a separate terminal:

```bash
npm run dev
```

## Demo Credentials

After running `php artisan migrate --seed`, use the following local/demo accounts. All use the password `password`.

| Role | Email | Password |
| --- | --- | --- |
| Administrator | `admin@jobportal.com` | `password` |
| Moderator | `moderator@jobportal.com` | `password` |
| Employer | `employer1@jobportal.com` | `password` |
| Job Seeker | `jobseeker1@jobportal.com` | `password` |

> These credentials are for local development and demonstrations only. Delete or change seeded accounts before any production deployment.

## API Authentication

The project exposes token-authentication endpoints under `/api`:

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `POST` | `/api/register` | Register a job seeker and receive a Sanctum token |
| `POST` | `/api/login` | Log in and receive a Sanctum token |
| `POST` | `/api/logout` | Revoke the current token (authentication required) |
| `GET` | `/api/profile` | Retrieve the authenticated user (authentication required) |

Send protected requests with:

```http
Authorization: Bearer YOUR_TOKEN
```

## Testing

```bash
cd laravel12
composer test
```

## Deployment Notes

For a typical Laravel deployment, set the web server document root to:

```text
laravel12/public
```

Set `APP_ENV=production`, `APP_DEBUG=false`, a production `APP_URL`, and secure database/mail credentials in `.env`. Then build assets and run migrations with `--force`:

```bash
npm ci
npm run build
php artisan migrate --force
php artisan optimize
```

See [DEPLOYMENT_INSTRUCTIONS.md](DEPLOYMENT_INSTRUCTIONS.md) for the existing cPanel-focused deployment notes.

## Security Notes

- Never commit `.env`, database passwords, mail credentials, or API tokens.
- Keep the web server pointed only to `laravel12/public`.
- Use unique, strong passwords and remove the demo accounts before production use.
- Set correct write permissions for `storage/` and `bootstrap/cache/` on the server.

## License

This project was created as a PHP/Laravel developer assignment.
