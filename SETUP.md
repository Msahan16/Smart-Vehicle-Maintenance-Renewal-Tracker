# 🚀 Quick Setup Guide - Smart Vehicle Tracker

## Step-by-Step Installation

### 1️⃣ Install Dependencies

```powershell
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 2️⃣ Environment Setup

```powershell
# Copy environment file
Copy-Item .env.example .env

# Generate application key
php artisan key:generate
```

### 3️⃣ Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_tracker
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create database:

```sql
CREATE DATABASE vehicle_tracker;
```

Run migrations:

```powershell
php artisan migrate
```

### 4️⃣ Configure Email (Optional)

For Gmail, edit `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
```

**Note:** Generate App Password from Google Account Settings > Security > 2-Step Verification > App Passwords

### 5️⃣ Storage Setup

```powershell
# Create storage link for file uploads
php artisan storage:link
```

### 6️⃣ Start Development Server

```powershell
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Asset compilation (new terminal)
npm run dev
```

### 7️⃣ Access Application

Open browser and navigate to:
```
http://localhost:8000
```

You should see the **vehicle-themed login page** (not default Laravel page)!

## 🎯 First Steps After Installation

1. **Register Account**: Click "Register Now" on login page
2. **Add Vehicle**: Navigate to "My Vehicles" and click "Add New Vehicle"
3. **Set Renewal Dates**: Enter license, insurance, and emission test expiry dates
4. **View Dashboard**: Check color-coded renewal alerts

## 🔧 Testing Email Reminders

Manual test:

```powershell
php artisan renewals:check
```

Check `storage/logs/laravel.log` for email sending status.

## ⏰ Setting Up Automated Reminders

### Windows Task Scheduler

1. Open Task Scheduler
2. Create Basic Task
3. Trigger: Daily
4. Action: Start a program
5. Program: `C:\path\to\php.exe`
6. Arguments: `C:\path\to\project\artisan schedule:run`

### Linux/Mac Cron

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## 📊 Database Tables Created

- ✅ `users` - User accounts with driver license info
- ✅ `vehicles` - Vehicle details with renewal dates
- ✅ `maintenance_records` - Service history
- ✅ `notifications` - System notifications
- ✅ `sessions` - User sessions
- ✅ `jobs` - Queue jobs for emails
- ✅ `cache` - Application cache

## 🎨 Key Features Implemented

### ✅ Authentication
- Custom vehicle-themed login page
- Registration with validation
- Password reset support

### ✅ Dashboard
- Color-coded status: 🟢 Safe | 🟡 Due Soon | 🔴 Overdue
- Statistics cards
- Upcoming renewals table
- Recent maintenance history

### ✅ Vehicle Management
- Add/Edit/Delete vehicles
- Photo upload
- Track multiple renewal dates
- Fuel type selection

### ✅ Automated Reminders
- Email notifications at 30, 7, 1, 0 days before expiry
- Professional email templates
- Queue-based sending
- User notification preferences

## 🐛 Common Issues & Solutions

### Issue: "Class not found" errors
**Solution:**
```powershell
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Issue: Images not uploading
**Solution:**
```powershell
php artisan storage:link
```

### Issue: Database connection error
**Solution:**
- Verify MySQL is running
- Check database credentials in `.env`
- Ensure database exists

### Issue: Assets not loading
**Solution:**
```powershell
npm run dev
# or for production
npm run build
```

## 📝 Default Login Credentials

After registration, use your created credentials.

**Test Account:** Create via registration page

## 🔐 Security Checklist

- [x] Password hashing (bcrypt)
- [x] CSRF protection
- [x] SQL injection prevention (Eloquent ORM)
- [x] XSS protection (Blade escaping)
- [x] Authentication middleware
- [x] Email verification ready

## 🚀 Production Deployment

1. **Environment:**
```env
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize:**
```powershell
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

3. **Set permissions:**
```bash
chmod -R 755 storage bootstrap/cache
```

4. **Configure web server** to point to `public` directory

## 📞 Need Help?

Check:
1. `README.md` - Full documentation
2. `storage/logs/laravel.log` - Error logs
3. Laravel documentation - https://laravel.com/docs
4. Livewire documentation - https://livewire.laravel.com

---

**🎉 You're all set! Start tracking your vehicles!**
