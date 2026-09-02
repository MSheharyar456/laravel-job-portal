# 🎯 BrainBrick Job Portal

A complete **Laravel 12** job portal application with role-based authentication system. Built with modern UI and purple theme (#6C5CE7).

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange?style=flat-square&logo=mysql)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## ✨ Features

### 🎭 Four User Roles
1. **Admin** - Full system control (users, jobs, categories management)
2. **Moderator** - Approve/reject job postings
3. **Employer** - Post jobs, manage applications
4. **Job Seeker** - Browse & apply for jobs, track applications

### 💼 Core Functionality
- ✅ Complete authentication system (login, register, logout)
- ✅ Role-based access control with middleware
- ✅ Job posting with category support
- ✅ Job moderation workflow (pending → approved/rejected)
- ✅ Application management system
- ✅ User profile management
- ✅ Dashboard for each role with statistics
- ✅ Resume upload functionality
- ✅ Job search & filtering

### 🎨 Design
- Modern purple theme (#6C5CE7)
- DM Sans & Inter fonts
- Fully responsive design
- Clean and intuitive UI
- Gradient effects & modern components

---

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/     # All controllers (Auth, Admin, Employer, etc.)
│   ├── Models/               # Eloquent models (User, Job, Application, etc.)
│   └── Middleware/           # Role-based middleware
├── database/
│   ├── migrations/           # 6 custom tables + Laravel defaults
│   └── seeders/              # Test data seeder
├── resources/views/          # 20+ Blade templates
│   ├── admin/               # Admin panel views
│   ├── moderator/           # Moderator views
│   ├── employer/            # Employer dashboard & job management
│   ├── job-seeker/          # Job seeker views
│   ├── jobs/                # Public job listings
│   └── layouts/app.blade.php # Master layout
└── routes/web.php           # All application routes
```

---

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0+
- Node.js & NPM (for assets)

### Step 1: Clone Repository
```bash
git clone https://github.com/YOUR_USERNAME/brainbrick-job-portal.git
cd brainbrick-job-portal
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
npm run build
```

### Step 3: Environment Setup
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=brainbrick_jobportal
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Migrations & Seeder
```bash
# Run migrations (creates all tables)
php artisan migrate

# Seed database with test data
php artisan db:seed
```

### Step 6: Create Storage Symlink
```bash
php artisan storage:link
```

### Step 7: Start Development Server
```bash
php artisan serve
```

Visit: http://127.0.0.1:8000

---

## 👥 Test Accounts

After running the seeder, use these accounts:

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@jobportal.com | password |
| **Moderator** | moderator@jobportal.com | password |
| **Employer** | employer1@jobportal.com | password |
| **Job Seeker** | jobseeker1@jobportal.com | password |

---

## 📊 Database Schema

### Tables
1. **users** - All user accounts with role field
2. **employers** - Employer profiles (company info)
3. **job_seeker_profiles** - Job seeker profiles (skills, experience)
4. **categories** - Job categories
5. **jobs** - Job postings
6. **job_applications** - Application tracking

### Relationships
- User → Employer (1:1)
- User → JobSeekerProfile (1:1)
- Employer → Jobs (1:Many)
- Job → Applications (1:Many)
- Category → Jobs (1:Many)

---

## 🔐 Security Features

- ✅ Password hashing with bcrypt
- ✅ CSRF protection on all forms
- ✅ Role-based middleware
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade escaping)
- ✅ Session security

---

## 🎯 User Workflows

### 1️⃣ Employer Workflow
1. Register as Employer
2. Complete company profile
3. Post new job
4. Wait for moderator approval
5. View applications when job is approved
6. Update application status (reviewed/shortlisted/rejected)

### 2️⃣ Job Seeker Workflow
1. Register as Job Seeker
2. Update profile (skills, resume, education)
3. Browse approved jobs
4. Apply for jobs
5. Track application status

### 3️⃣ Moderator Workflow
1. Login to moderator dashboard
2. View pending jobs
3. Review job details
4. Approve or reject jobs

### 4️⃣ Admin Workflow
1. Manage all users (view, delete, change roles)
2. Manage all jobs (approve, reject, delete)
3. Manage categories (create, edit, delete)
4. View system statistics

---

## 📱 API Endpoints

All routes are in `routes/web.php`:

### Public Routes
- `GET /` - Homepage
- `GET /jobs` - Browse jobs
- `GET /jobs/{id}` - Job details
- `GET /login` - Login page
- `POST /login` - Login submit
- `GET /register` - Register page
- `POST /register` - Register submit

### Protected Routes (with role middleware)
- `/admin/*` - Admin dashboard & management
- `/moderator/*` - Moderator job review
- `/employer/*` - Employer dashboard & job posting
- `/job-seeker/*` - Job seeker dashboard & applications

---

## 🛠️ Technology Stack

### Backend
- **Laravel 12.x** - PHP Framework
- **MySQL** - Database
- **Eloquent ORM** - Database queries

### Frontend
- **Blade Templates** - Templating engine
- **Tailwind CSS** - Utility-first CSS
- **Alpine.js** (optional) - Lightweight JS

### Tools
- **Composer** - PHP dependency manager
- **NPM** - Node package manager
- **Vite** - Frontend build tool

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Developer

**Developed by:** Your Name  
**For:** BrainBrick Company Interview  
**Date:** September 2026  
**Contact:** your.email@example.com

---

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- BrainBrick Company for the opportunity

---

## 📞 Support

For any questions or issues, please contact:
- Email: your.email@example.com
- GitHub Issues: [Create an issue](https://github.com/YOUR_USERNAME/brainbrick-job-portal/issues)

---

**⭐ If you find this project useful, please give it a star on GitHub!**
