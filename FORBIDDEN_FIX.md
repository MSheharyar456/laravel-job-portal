# Forbidden Error - Complete Fix Guide

## 🔧 Files Updated to Fix Forbidden Error

The following files have been created/updated to resolve the "403 Forbidden" error:

1. ✅ **tst/.htaccess** - Updated with proper rewrite rules and directory access
2. ✅ **tst/index.php** - Created fallback entry point
3. ✅ **tst/laravel12/public/.htaccess** - Enhanced with proper permissions
4. ✅ **Directory permissions** - Already set locally

---

## 🚀 Quick Solutions (Try in Order)

### Solution 1: Clear Browser Cache & Test
Sometimes the error is cached in your browser.

**Steps:**
1. Clear your browser cache (Ctrl+F5 or Cmd+Shift+R)
2. Visit: https://tst.sheharyarcodes.com
3. Or try in incognito/private mode

---

### Solution 2: Check Server Permissions (MOST COMMON FIX)

On your server, set these permissions via SSH:

```bash
cd /path/to/tst
chmod 755 laravel12
chmod 755 laravel12/public
chmod 644 laravel12/public/index.php
chmod 644 .htaccess
chmod 644 index.php
chmod -R 755 laravel12/storage
chmod -R 755 laravel12/bootstrap/cache
```

**Via cPanel File Manager:**
1. Navigate to your `tst` folder
2. Right-click on folders → Change Permissions → Set to **755**
3. Right-click on files → Change Permissions → Set to **644**
4. Important directories:
   - `laravel12/` → 755
   - `laravel12/public/` → 755
   - `laravel12/storage/` → 755 (recursive)
   - `laravel12/bootstrap/cache/` → 755 (recursive)

---

### Solution 3: Change Document Root (BEST SOLUTION)

**Via cPanel:**
1. Go to "Domains" or "Subdomains"
2. Find `tst.sheharyarcodes.com`
3. Click "Manage" or "Edit"
4. Change "Document Root" to: `/home/yourusername/public_html/tst/laravel12/public`
5. Save changes
6. Wait 2-3 minutes for changes to propagate

**This is the BEST solution because:**
- Proper Laravel setup
- Better security
- Best performance
- No .htaccess redirects needed

---

### Solution 4: Verify .htaccess is Working

Test if mod_rewrite is enabled on your server.

**Create a test file:** `tst/test.php`
```php
<?php
phpinfo();
```

Visit: `https://tst.sheharyarcodes.com/test.php`

Look for:
- **Loaded Modules** section
- Find `mod_rewrite` - should be listed
- If NOT found, contact your hosting provider

**Delete test.php after checking!**

---

### Solution 5: Alternative .htaccess (if Solution 3 doesn't work)

If you can't change the document root, replace `tst/.htaccess` with this:

```apache
#+PHPVersion
#=php83
AddHandler x-httpd-php83 .php
#-PHPVersion

DirectoryIndex index.php

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # If requesting the root, show Laravel
    RewriteRule ^$ laravel12/public/index.php [L]
    
    # If file exists in public, serve it
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    # Otherwise route through Laravel
    RewriteRule ^(.*)$ laravel12/public/index.php [L,QSA]
</IfModule>
```

---

### Solution 6: Check File Ownership (SSH Access)

If you have SSH access:

```bash
cd /path/to/tst
chown -R yourusername:yourusername laravel12/
chown yourusername:yourusername .htaccess
chown yourusername:yourusername index.php
```

Replace `yourusername` with your actual cPanel username.

**Or set to web server user:**
```bash
chown -R www-data:www-data laravel12/
# OR
chown -R apache:apache laravel12/
# OR  
chown -R nobody:nobody laravel12/
```

---

### Solution 7: Disable SELinux (if on CentOS/RHEL server)

If on a CentOS/RHEL server with SELinux:

```bash
# Check SELinux status
getenforce

# If returns "Enforcing", temporarily disable:
sudo setenforce 0

# Test your site
# If it works, you need to set proper SELinux contexts:
sudo chcon -R -t httpd_sys_content_t /path/to/tst/laravel12/
sudo chcon -R -t httpd_sys_rw_content_t /path/to/tst/laravel12/storage/
sudo chcon -R -t httpd_sys_rw_content_t /path/to/tst/laravel12/bootstrap/cache/
```

---

## 🔍 Diagnostic Steps

### Check What's Actually Forbidden

**Test each level:**

1. **Test tst root:**
   - Visit: `https://tst.sheharyarcodes.com/test.html`
   - Create a simple test.html: `<h1>Test Works</h1>`
   - If this shows "Forbidden" → Problem is with tst folder permissions

2. **Test laravel12/public:**
   - Visit: `https://tst.sheharyarcodes.com/laravel12/public/`
   - If this shows Laravel page → .htaccess redirect is not working
   - If this shows "Forbidden" → Problem is with laravel12/public permissions

3. **Test index.php directly:**
   - Visit: `https://tst.sheharyarcodes.com/index.php`
   - Should redirect to Laravel
   - If shows "Forbidden" → Problem is with index.php permissions

---

## 📋 Checklist for Server Upload

When uploading to server, verify:

- [ ] All files uploaded completely (check file sizes)
- [ ] `.htaccess` files uploaded (they're hidden files!)
- [ ] `.env` file uploaded
- [ ] `vendor/` folder uploaded (or run `composer install` on server)
- [ ] File permissions set correctly (755 for directories, 644 for files)
- [ ] Document root points to `laravel12/public` (preferred)
- [ ] mod_rewrite enabled on server
- [ ] PHP 8.2+ installed and active

---

## 🔐 Security Note

The files we created include:
- **index.php** in tst root → Safe, it's a redirect
- **.htaccess** rules → Safe, properly configured
- **No directory listing** → Secure (Options -Indexes in public/.htaccess)

---

## 🆘 Still Getting Forbidden?

### Check Server Error Logs

**Via cPanel:**
1. Go to "Metrics" → "Errors"
2. Look for recent 403 errors
3. The log will show the exact reason

**Via SSH:**
```bash
tail -f /var/log/apache2/error.log
# OR
tail -f /var/log/httpd/error_log
```

**Common error messages and fixes:**
- `"Options FollowSymLinks or SymLinksIfOwnerMatch is off"` → Add `Options +FollowSymLinks` to .htaccess
- `"Symbolic link not allowed"` → Use real paths, not symlinks
- `"Permission denied"` → Fix file permissions (chmod 755/644)
- `".htaccess: Invalid command"` → mod_rewrite not enabled

---

## 📞 Contact Hosting Support

If nothing works, contact your hosting provider and ask:

1. "Is mod_rewrite enabled for my account?"
2. "Can I change the document root for my subdomain?"
3. "Are there any permission restrictions on my account?"
4. "Can you check why I'm getting a 403 Forbidden error on tst.sheharyarcodes.com?"

---

## ✅ Expected Result

After fixing, visiting `https://tst.sheharyarcodes.com` should show:
- **Laravel welcome page** (if you haven't built anything yet)
- OR your Laravel application home page

---

## 📝 Summary of What We Did

1. ✅ Created proper .htaccess redirect in tst root
2. ✅ Created fallback index.php in tst root  
3. ✅ Enhanced laravel12/public/.htaccess with permissions
4. ✅ Set proper directory permissions locally
5. ✅ Configured for PHP 8.3

**The most likely fix on server: Set file permissions to 755/644 and/or change document root to laravel12/public**

---

**Last Updated:** September 2, 2026  
**Laravel Version:** 12.12.2
