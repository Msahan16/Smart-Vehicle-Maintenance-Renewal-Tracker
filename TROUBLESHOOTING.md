# 🔧 Troubleshooting Guide - Smart Vehicle Tracker

## 🚨 Common Issues & Solutions

---

## 1️⃣ Installation Issues

### ❌ "composer: command not found"

**Problem**: Composer is not installed or not in PATH

**Solution**:
```powershell
# Download and install Composer from:
# https://getcomposer.org/download/

# Verify installation:
composer --version
```

---

### ❌ "npm: command not found"

**Problem**: Node.js/NPM not installed

**Solution**:
```powershell
# Download and install Node.js from:
# https://nodejs.org/

# Verify installation:
node --version
npm --version
```

---

### ❌ "Class 'Illuminate\Foundation\Application' not found"

**Problem**: Composer dependencies not installed

**Solution**:
```powershell
composer install
composer dump-autoload
```

---

## 2️⃣ Database Issues

### ❌ "SQLSTATE[HY000] [1045] Access denied"

**Problem**: Wrong database credentials

**Solution**:
1. Check `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_tracker
DB_USERNAME=root
DB_PASSWORD=your_password
```

2. Verify MySQL is running:
```powershell
# Check MySQL service
Get-Service -Name MySQL*
```

3. Test connection:
```powershell
php artisan db:show
```

---

### ❌ "SQLSTATE[HY000] [2002] No connection could be made"

**Problem**: MySQL server not running

**Solution**:
```powershell
# Start MySQL service (Windows)
Start-Service -Name MySQL80  # or MySQL57, check your version

# Or use XAMPP/WAMP/MAMP control panel
```

---

### ❌ "Base table or view not found"

**Problem**: Migrations not run

**Solution**:
```powershell
# Run migrations
php artisan migrate

# If tables exist but corrupted:
php artisan migrate:fresh  # WARNING: Deletes all data
```

---

### ❌ "Database [vehicle_tracker] does not exist"

**Problem**: Database not created

**Solution**:
```sql
-- Connect to MySQL and run:
CREATE DATABASE vehicle_tracker;
```

Or using command line:
```powershell
mysql -u root -p -e "CREATE DATABASE vehicle_tracker;"
```

---

## 3️⃣ Authentication Issues

### ❌ Login page keeps redirecting back

**Problem**: Session/cache issue

**Solution**:
```powershell
php artisan config:clear
php artisan cache:clear
php artisan session:table  # If using database sessions
php artisan migrate
```

---

### ❌ "419 | Page Expired" on login

**Problem**: CSRF token expired

**Solution**:
1. Clear browser cookies
2. Clear Laravel cache:
```powershell
php artisan config:clear
php artisan cache:clear
```
3. Check `.env` has `APP_KEY` set:
```powershell
php artisan key:generate
```

---

### ❌ "Route [login] not defined"

**Problem**: Routes not loaded

**Solution**:
```powershell
php artisan route:clear
php artisan route:cache
php artisan route:list  # Verify routes exist
```

---

## 4️⃣ Email Issues

### ❌ Emails not sending

**Problem**: SMTP configuration or connection issue

**Solution**:

1. **Check `.env` configuration**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # NOT your Gmail password!
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

2. **For Gmail, use App Password**:
   - Go to Google Account Settings
   - Security > 2-Step Verification
   - App Passwords > Generate
   - Copy 16-character password to `.env`

3. **Test email**:
```powershell
php artisan tinker
```
```php
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

4. **Check logs**:
```powershell
Get-Content storage\logs\laravel.log -Tail 50
```

---

### ❌ "Connection could not be established with host smtp.gmail.com"

**Problem**: Network/firewall blocking SMTP

**Solution**:
1. Check firewall settings
2. Try different port (465 instead of 587)
3. Verify internet connection
4. Test with different email provider (Mailtrap, SendGrid)

---

### ❌ Queue jobs not processing

**Problem**: Queue worker not running

**Solution**:
```powershell
# Start queue worker
php artisan queue:work

# Or for auto-restart:
php artisan queue:listen

# Check failed jobs:
php artisan queue:failed

# Retry failed jobs:
php artisan queue:retry all
```

---

## 5️⃣ File Upload Issues

### ❌ "The file could not be uploaded"

**Problem**: Storage link missing or permissions

**Solution**:
```powershell
# Create storage link
php artisan storage:link

# Verify link exists
Test-Path public\storage  # Should return True

# Check permissions (Linux/Mac):
# chmod -R 775 storage
```

---

### ❌ Uploaded images not displaying

**Problem**: Storage link broken or missing

**Solution**:
1. Delete existing link:
```powershell
Remove-Item public\storage -Force
```

2. Recreate link:
```powershell
php artisan storage:link
```

3. Verify in blade templates:
```php
<img src="{{ Storage::url($vehicle->photo) }}" />
```

---

## 6️⃣ Livewire Issues

### ❌ "Livewire component not found"

**Problem**: Component class missing or namespace wrong

**Solution**:
```powershell
# Create component
php artisan make:livewire ComponentName

# Check namespace in component file
# Should be: namespace App\Livewire;

# Dump autoload
composer dump-autoload
```

---

### ❌ Livewire form not submitting

**Problem**: CSRF or wire:submit issue

**Solution**:
1. Check form has `wire:submit.prevent`:
```blade
<form wire:submit.prevent="save">
```

2. Clear cache:
```powershell
php artisan view:clear
php artisan cache:clear
```

---

## 7️⃣ Asset/UI Issues

### ❌ CSS/JS not loading

**Problem**: Assets not compiled

**Solution**:
```powershell
# Kill existing npm process
# Ctrl+C

# Rebuild assets
npm run dev

# Or for production:
npm run build
```

---

### ❌ "Vite manifest not found"

**Problem**: Assets not built

**Solution**:
```powershell
npm install
npm run build
```

---

### ❌ Bootstrap styles not applying

**Problem**: CDN blocked or assets not loading

**Solution**:
1. Check internet connection (using CDN)
2. View page source, verify CSS links
3. Check browser console for errors (F12)

---

## 8️⃣ Scheduler Issues

### ❌ Scheduled tasks not running

**Problem**: Cron/Task Scheduler not set up

**Solution**:

**Windows**:
1. Open Task Scheduler
2. Create Basic Task
3. Trigger: Daily, Repeat every 1 minute
4. Action: Start program
5. Program: `C:\path\to\php.exe`
6. Arguments: `C:\path\to\project\artisan schedule:run`

**Linux/Mac**:
```bash
crontab -e
# Add line:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

**Manual Test**:
```powershell
php artisan schedule:run
php artisan renewals:check
```

---

### ❌ "Class 'App\Console\Commands\CheckRenewals' not found"

**Problem**: Command not registered

**Solution**:
```powershell
composer dump-autoload
php artisan list  # Verify command shows up
```

---

## 9️⃣ Permission Issues (Linux/Mac)

### ❌ "Permission denied" errors

**Solution**:
```bash
# Set ownership
sudo chown -R www-data:www-data /path/to/project

# Set permissions
sudo chmod -R 755 /path/to/project
sudo chmod -R 775 /path/to/project/storage
sudo chmod -R 775 /path/to/project/bootstrap/cache
```

---

## 🔟 Performance Issues

### ❌ Application slow to load

**Solution**:
```powershell
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# For development:
php artisan optimize:clear
```

---

## 🆘 Nuclear Option (Start Fresh)

If nothing works, start from scratch:

```powershell
# 1. Clear everything
php artisan optimize:clear
composer dump-autoload

# 2. Reinstall dependencies
Remove-Item -Recurse -Force vendor
Remove-Item -Recurse -Force node_modules
composer install
npm install

# 3. Rebuild database
php artisan migrate:fresh

# 4. Rebuild assets
npm run build

# 5. Clear browser cache and cookies

# 6. Restart server
php artisan serve
```

---

## 🔍 Debugging Tools

### Check Application Status
```powershell
# View routes
php artisan route:list

# View config
php artisan config:show

# Check database
php artisan db:show

# View environment
php artisan env

# Test queue
php artisan queue:work --once
```

### View Logs
```powershell
# Real-time log viewing
Get-Content storage\logs\laravel.log -Wait -Tail 50

# Search for errors
Select-String -Path storage\logs\laravel.log -Pattern "ERROR|CRITICAL"

# View last 100 lines
Get-Content storage\logs\laravel.log -Tail 100
```

### Browser Developer Tools
1. Press F12
2. Check Console tab for JavaScript errors
3. Check Network tab for failed requests
4. Check Application > Storage for session issues

---

## 📞 Getting Help

### Before Asking for Help:

1. ✅ Check error logs: `storage/logs/laravel.log`
2. ✅ Try clearing cache: `php artisan optimize:clear`
3. ✅ Verify `.env` configuration
4. ✅ Check composer/npm dependencies installed
5. ✅ Read error message carefully

### Useful Information to Provide:

- Full error message
- What you were trying to do
- What you've already tried
- Output of `php artisan --version`
- Output of `composer show`
- Relevant code snippets
- Log file contents

---

## 📚 Resources

- **Laravel Docs**: https://laravel.com/docs
- **Livewire Docs**: https://livewire.laravel.com
- **Bootstrap Docs**: https://getbootstrap.com
- **Stack Overflow**: Search error messages
- **Laravel Community**: laracasts.com, reddit.com/r/laravel

---

## ✅ Verification Checklist

Before reporting an issue, verify:

- [ ] PHP version 8.2 or higher: `php --version`
- [ ] Composer installed: `composer --version`
- [ ] Node.js installed: `node --version`
- [ ] Dependencies installed: `vendor/` and `node_modules/` exist
- [ ] `.env` file exists and configured
- [ ] `APP_KEY` generated: Check `.env`
- [ ] Database created and migrated
- [ ] Storage link created: `php artisan storage:link`
- [ ] Assets compiled: `npm run dev` or `npm run build`
- [ ] Server running: `php artisan serve`
- [ ] No console errors: Check browser F12

---

**Most issues can be fixed by clearing cache and reinstalling dependencies! 🔧**
