# Laravel 12 Deployment Instructions for TST Subdomain

## Overview
This document provides step-by-step instructions for deploying your Laravel 12 application to the **tst.sheharyarcodes.com** subdomain.

---

## Local Setup Complete ✓

The following has been completed locally:
- ✅ Laravel 12.12.2 installed in `tst/laravel12/`
- ✅ `.htaccess` configured with PHP 8.3
- ✅ `.env` file configured for production
- ✅ Storage and bootstrap/cache permissions set

---

## Pre-Deployment Checklist

### 1. **Prepare Your Server**
- Ensure your subdomain `tst.sheharyarcodes.com` is created and pointing to the correct directory
- Verify PHP 8.2+ is available on your server
- Confirm Composer is installed on the server (or you'll upload vendor folder)

### 2. **Create Database** (if using MySQL)
- Log into your hosting control panel (cPanel/Plesk)
- Create a new MySQL database
- Create a database user with full privileges
- Note down: database name, username, password, and host

---

## Deployment Steps

### Step 1: Upload Files to Server

**Option A: Using FTP/SFTP (Recommended for first-time deployment)**
1. Connect to your server via FTP/SFTP client (FileZilla, WinSCP, etc.)
2. Navigate to your `tst` subdomain root directory
3. Upload the entire `tst` folder contents to the server
   - Upload `laravel12/` folder (all contents)
   - Upload `.htaccess` file

**Option B: Using cPanel File Manager**
1. Compress the `tst` folder locally into a ZIP file
2. Upload the ZIP file via cPanel File Manager
3. Extract the ZIP file in the appropriate directory

**Important Files to Upload:**
```
tst/
├── .htaccess
└── laravel12/
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    ├── artisan
    ├── composer.json
    └── ... (all other files)
```

---

### Step 2: Configure .env File on Server

1. Navigate to `tst/laravel12/.env` on your server
2. Update database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=your-database-host.com
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

3. Verify other settings:
```env
APP_NAME="TST Laravel App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tst.sheharyarcodes.com
```

---

### Step 3: Set Directory Permissions

**Via SSH (recommended):**
```bash
cd /path/to/tst/laravel12
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

**Via cPanel File Manager:**
1. Right-click on `storage` folder → Change Permissions → Set to `755`
2. Right-click on `bootstrap/cache` folder → Change Permissions → Set to `755`

---

### Step 4: Configure Web Root

Your web server needs to point to the Laravel `public` directory.

**Option A: Update Document Root (Recommended)**
1. In your hosting control panel, set the document root for `tst.sheharyarcodes.com` to:
   ```
   /path/to/tst/laravel12/public
   ```

**Option B: Create .htaccess Redirect (if you can't change document root)**
Create/update `.htaccess` in the `tst` root folder:
```apache
#+PHPVersion
#=php83
AddHandler x-httpd-php83 .php
#-PHPVersion

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ laravel12/public/$1 [L]
</IfModule>
```

---

### Step 5: Install Dependencies (if vendor not uploaded)

If you didn't upload the `vendor` folder, run via SSH:
```bash
cd /path/to/tst/laravel12
composer install --optimize-autoloader --no-dev
```

---

### Step 6: Run Database Migrations

**Via SSH:**
```bash
cd /path/to/tst/laravel12
php artisan migrate --force
```

**Via Browser (if SSH not available):**
- Create a temporary migration file `migrate.php` in the public folder:
```php
<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->call('migrate', ['--force' => true]);
echo "Migration completed with status: " . $status;
```
- Visit: `https://tst.sheharyarcodes.com/migrate.php`
- **Delete this file immediately after use!**

---

### Step 7: Optimize Laravel for Production

**Via SSH:**
```bash
cd /path/to/tst/laravel12

# Clear and cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

### Step 8: Verify Installation

1. Visit: `https://tst.sheharyarcodes.com`
2. You should see the Laravel welcome page
3. Check for any errors in `storage/logs/laravel.log`

---

## Post-Deployment Configuration

### Security Checklist
- [ ] Verify `.env` file is not publicly accessible
- [ ] Ensure `APP_DEBUG=false` in production
- [ ] Set up SSL certificate (HTTPS)
- [ ] Configure CSRF protection
- [ ] Set secure session settings

### Performance Optimization
- [ ] Enable OPcache on server
- [ ] Configure queue workers if using queues
- [ ] Set up Laravel scheduler (cron jobs) if needed
- [ ] Consider using Redis/Memcached for caching

### Monitoring
- [ ] Set up error logging and monitoring
- [ ] Configure log rotation for `storage/logs`
- [ ] Monitor database performance

---

## Troubleshooting

### Issue: "500 Internal Server Error"
**Solutions:**
1. Check `.env` file exists and is configured correctly
2. Verify storage and bootstrap/cache permissions (755 or 775)
3. Check error logs: `storage/logs/laravel.log`
4. Clear all caches: `php artisan cache:clear`

### Issue: "Database Connection Error"
**Solutions:**
1. Verify database credentials in `.env`
2. Ensure database exists and user has proper permissions
3. Test database connection from server
4. Check if database host allows connections from web server

### Issue: "404 Not Found" for all routes
**Solutions:**
1. Verify `.htaccess` exists in `public` folder
2. Check if `mod_rewrite` is enabled on server
3. Ensure document root points to `laravel12/public`
4. Clear route cache: `php artisan route:clear`

### Issue: "Permission Denied" errors
**Solutions:**
1. Set correct permissions: `chmod -R 755 storage bootstrap/cache`
2. Ensure web server user owns files: `chown -R www-data:www-data /path/to/laravel12`

---

## Maintenance Commands

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Update Application
```bash
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Structure Overview

```
tst.sheharyarcodes.com/
├── .htaccess                 # PHP version handler
└── laravel12/
    ├── app/                  # Application code
    ├── bootstrap/            # Bootstrap files
    │   └── cache/           # Framework cache (needs write permission)
    ├── config/              # Configuration files
    ├── database/            # Migrations and seeders
    ├── public/              # Web root (should be document root)
    │   ├── index.php       # Entry point
    │   └── .htaccess       # URL rewriting
    ├── resources/           # Views, assets
    ├── routes/              # Route definitions
    ├── storage/             # Storage (needs write permission)
    │   ├── app/
    │   ├── framework/
    │   └── logs/
    ├── vendor/              # Composer dependencies
    ├── .env                 # Environment configuration
    └── artisan              # CLI tool
```

---

## Additional Resources

- [Laravel Deployment Documentation](https://laravel.com/docs/12.x/deployment)
- [Laravel Configuration](https://laravel.com/docs/12.x/configuration)
- [Laravel Database](https://laravel.com/docs/12.x/database)

---

## Support

If you encounter issues:
1. Check `storage/logs/laravel.log` for detailed error messages
2. Verify server requirements (PHP 8.2+, required extensions)
3. Contact your hosting provider for server-specific configurations

---

**Deployment Date:** September 2, 2026  
**Laravel Version:** 12.12.2  
**PHP Version Required:** 8.2+
