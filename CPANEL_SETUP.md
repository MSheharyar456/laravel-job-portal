# 🚀 cPanel Setup Guide - TST Subdomain

## ✅ Current Status: Laravel is Working!

You're seeing the Laravel welcome page, which means everything is installed correctly! Now you just need to configure cPanel properly so visitors access it directly at `https://tst.sheharyarcodes.com` instead of `https://tst.sheharyarcodes.com/laravel12/public/`.

---

## 🎯 BEST Solution: Change Document Root in cPanel

This is how your `chatbot` subdomain is configured, and it's the PROPER Laravel way.

### Step-by-Step Instructions:

#### **Method 1: Using Subdomain Manager**

1. **Login to cPanel**
   - Go to your hosting control panel

2. **Open Subdomain Manager**
   - Search for "Subdomains" or "Subdomain Manager"
   - Click on it

3. **Find Your TST Subdomain**
   - Look for `tst.sheharyarcodes.com` in the list
   - Click **"Manage"** or **"Edit"** button next to it

4. **Change Document Root**
   - Find the field labeled **"Document Root"**
   - Current value is probably: `/public_html/tst`
   - **Change it to:** `/public_html/tst/laravel12/public`
   - Click **"Save"** or **"Update"**

5. **Wait & Test**
   - Wait 2-3 minutes for DNS propagation
   - Clear your browser cache (Ctrl+F5)
   - Visit: `https://tst.sheharyarcodes.com`
   - You should see Laravel welcome page WITHOUT `/laravel12/public/` in URL

---

#### **Method 2: Using File Manager (Alternative)**

If you can't find the subdomain editor:

1. **Go to File Manager** in cPanel

2. **Navigate to:**
   ```
   /home/yourusername/public_html/
   ```

3. **Look for these files** (might be hidden):
   - `.htaccess`
   - `subdomains.conf`
   - Check if there's a symbolic link (symlink) for `tst`

4. **If `tst` is a symlink:**
   - Delete the current symlink
   - Create a NEW symlink pointing to: `public_html/tst/laravel12/public`

---

## 📊 How Your Chatbot is Set Up (For Reference)

Your working chatbot subdomain structure:

```
Subdomain: chatbot.sheharyarcodes.com
Document Root: /public_html/chatbot/laravel11/public

Directory Structure:
chatbot/
├── .htaccess (PHP version only)
└── laravel11/
    └── public/ ← Document root points HERE
```

## 🎯 How TST Should Be Set Up (Same Way)

```
Subdomain: tst.sheharyarcodes.com
Document Root: /public_html/tst/laravel12/public

Directory Structure:
tst/
├── .htaccess (redirect only, as fallback)
└── laravel12/
    └── public/ ← Document root should point HERE
```

---

## 🔍 How to Verify Document Root Setting

### **Check Current Setting:**

1. In cPanel Subdomain Manager, you'll see something like:

```
Subdomain          Document Root                      Action
─────────────────────────────────────────────────────────────
tst                /public_html/tst                   [Manage]
```

2. It should be:

```
Subdomain          Document Root                           Action
──────────────────────────────────────────────────────────────────
tst                /public_html/tst/laravel12/public       [Manage]
```

---

## ⚠️ If You Can't Change Document Root

Some hosting providers restrict document root changes. If that's the case:

### **Option A: Keep Current Setup (Using .htaccess)**

Your site is already working! Just access it as:
- `https://tst.sheharyarcodes.com/laravel12/public/`

The `.htaccess` we created will redirect automatically:
- `https://tst.sheharyarcodes.com/` → redirects to → `laravel12/public/`

**This works, but:**
- ❌ Not the cleanest URL structure
- ❌ Slightly slower (extra redirect)
- ✅ Works on all hosting providers

### **Option B: Move Files Up One Level**

If you want clean URLs without changing document root:

1. **Move everything from `laravel12/` to `tst/`:**
   ```bash
   mv laravel12/* ./
   mv laravel12/.* ./
   rmdir laravel12
   ```

2. **Update Document Root in cPanel to:** `/public_html/tst/public`

3. **Result:** 
   ```
   tst/
   ├── app/
   ├── public/
   ├── config/
   └── ... (all Laravel files at tst root)
   ```

**But this is messy and not recommended!**

---

## 📋 Recommended Steps (IN ORDER)

1. ✅ **Try Method 1** (Change document root in cPanel) - BEST
2. ✅ **If not available, use current setup** (works fine with .htaccess)
3. ✅ **Contact hosting support** if you want document root changed
4. ❌ **Don't use Option B** unless absolutely necessary

---

## 🎯 Expected Final Result

After changing document root properly:

**Visit:** `https://tst.sheharyarcodes.com`

**You'll see:**
- ✅ Laravel welcome page
- ✅ Clean URL (no /laravel12/public/ in path)
- ✅ All routes work properly
- ✅ Static assets load correctly

**URL Examples:**
```
https://tst.sheharyarcodes.com/          ← Home page
https://tst.sheharyarcodes.com/about     ← About page
https://tst.sheharyarcodes.com/api/users ← API endpoint
```

---

## 🆘 Troubleshooting

### Issue: "Still seeing /laravel12/public/ in URL"
**Solution:** 
- Clear browser cache (Ctrl+Shift+Delete)
- Try incognito/private mode
- Wait 5 minutes for DNS propagation

### Issue: "Can't find Subdomain Manager in cPanel"
**Solution:**
- Look for: "Domains", "Addon Domains", "Subdomains"
- Different cPanel themes have different names
- Ask your hosting provider for help

### Issue: "Don't have permission to change document root"
**Solution:**
- Contact your hosting support
- Or keep using current .htaccess redirect (it works!)

---

## 📞 What to Tell Your Hosting Support

If you contact support, say:

> "Hi, I need to change the document root for my subdomain tst.sheharyarcodes.com 
> from `/public_html/tst` to `/public_html/tst/laravel12/public`
> 
> This is for a Laravel application, and I need the public folder to be the web root.
> 
> My chatbot subdomain is already configured this way and works perfectly."

---

## ✅ Summary

**Current Status:** ✅ Laravel is working!  
**Current Access:** `https://tst.sheharyarcodes.com` (with .htaccess redirect)  
**Goal:** Direct access without redirect  
**Best Solution:** Change document root to `laravel12/public` in cPanel  
**Fallback:** Current .htaccess redirect works fine if you can't change it  

---

**You're almost done! Just one cPanel configuration change away from the perfect setup! 🎉**
