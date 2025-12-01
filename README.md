# 🚗 Smart Vehicle Maintenance & Renewal Tracker

A professional web application built with **Laravel 12** and **Livewire 3** to help users manage multiple vehicles, track maintenance records, monitor upcoming renewal deadlines, and receive automated email reminders.

![Vehicle Tracker](https://img.shields.io/badge/Laravel-12-red) ![Livewire](https://img.shields.io/badge/Livewire-3-purple) ![Bootstrap](https://img.shields.io/badge/Bootstrap-5-blue)

---

## 📋 Table of Contents

- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [System Requirements](#-system-requirements)
- [Installation Guide](#-installation-guide)
- [Configuration](#-configuration)
- [Database Setup](#-database-setup)
- [Running the Application](#-running-the-application)
- [Scheduled Tasks](#-scheduled-tasks)
- [Usage Guide](#-usage-guide)
- [Screenshots](#-screenshots)
- [Project Structure](#-project-structure)
- [Contributing](#-contributing)
- [License](#-license)

---

## ✨ Features

### 🔐 Authentication Module
- User registration with validation
- Secure login with remember me option
- Password reset functionality
- Email verification support
- **Vehicle-themed login/register pages** (not default Laravel design)

### 🚘 Vehicle Management
- Add, edit, and delete multiple vehicles
- Track vehicle details:
  - Vehicle number
  - Brand and model
  - Fuel type (Petrol, Diesel, Electric, Hybrid, CNG, LPG)
  - Engine capacity
  - Manufactured year
  - Color
  - Vehicle photo upload
- Monitor renewal dates:
  - Vehicle license expiry
  - Insurance expiry
  - Emission test expiry

### 🔧 Maintenance History
- Log service records with:
  - Service type (oil change, tire rotation, brake check, etc.)
  - Service date
  - Next due date (auto-calculated)
  - Mileage
  - Service center
  - Cost tracking
  - Notes
  - Invoice image upload

### 🔔 Automated Reminder System
- Email notifications for:
  - Next service date
  - Vehicle license renewal
  - Insurance renewal
  - Emission test renewal
  - Driver's license renewal
- Reminder schedule:
  - 30 days before expiry
  - 7 days before expiry
  - 1 day before expiry
  - On the day of expiry
- Queue-based email sending
- Notification preferences (enable/disable)

### 📊 Dashboard
- Overview statistics:
  - Total vehicles
  - Safe renewals (green)
  - Due soon renewals (yellow)
  - Overdue renewals (red)
- Upcoming renewals table with color-coded status
- Recent maintenance history
- Quick action buttons
- Visual alerts and progress indicators

### 👤 Profile & Settings
- Update user profile
- Change password
- Manage notification preferences
- Driver's license tracking

---

## 🛠 Technology Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **Laravel** | 12.x | Backend framework |
| **Livewire** | 3.x | Frontend reactivity |
| **MySQL** | 8.0+ | Database |
| **Bootstrap** | 5.3 | UI framework |
| **PHP** | 8.2+ | Server-side language |
| **Font Awesome** | 6.4 | Icons |

---

## 💻 System Requirements

- **PHP**: 8.2 or higher
- **Composer**: 2.x
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Apache/Nginx

---

## 📦 Installation Guide

### Step 1: Clone or Download Project

```bash
# Navigate to your project directory
cd d:\VT
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

### Step 4: Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env
```

Edit `.env` file and configure:

```env
APP_NAME="Smart Vehicle Tracker"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_tracker
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

---

## 🗄 Database Setup

### Step 1: Create Database

```sql
CREATE DATABASE vehicle_tracker;
```

### Step 2: Run Migrations

```bash
php artisan migrate
```

This will create the following tables:
- `users` - User accounts
- `vehicles` - Vehicle information
- `maintenance_records` - Service history
- `notifications` - System notifications
- `sessions` - User sessions
- `jobs` - Queue jobs
- `cache` - Application cache

---

## 🚀 Running the Application

### Development Server

```bash
# Terminal 1: Start Laravel development server
php artisan serve

# Terminal 2: Build assets (in another terminal)
npm run dev
```

Access the application at: **http://localhost:8000**

### Production Build

```bash
# Build assets for production
npm run build

# Configure your web server to point to the public directory
```

---

## ⏰ Scheduled Tasks

The application uses Laravel's task scheduler to send automated reminders.

### Setup Cron Job (Linux/Mac)

Add to your crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Manual Testing

To manually trigger the renewal check:

```bash
php artisan renewals:check
```

### Windows Task Scheduler

For Windows, create a scheduled task to run:

```bash
php artisan schedule:run
```

Every minute.

---

## 📖 Usage Guide

### 1. Register an Account

- Navigate to the registration page
- Fill in your details (name, email, password)
- Click "Create Account"
- You'll be redirected to the dashboard

### 2. Add Your First Vehicle

- Click "Add New Vehicle" from the dashboard
- Fill in vehicle details:
  - Vehicle number (required)
  - Brand and model (required)
  - Fuel type (required)
  - Year manufactured (required)
  - Optional: color, engine capacity, photo
  - Set renewal dates for license, insurance, emission test
- Click "Save Vehicle"

### 3. Log Maintenance Records

- Navigate to "Maintenance" from the sidebar
- Click "Log Maintenance"
- Select vehicle
- Enter service details:
  - Service type
  - Service date
  - Next due date
  - Cost and mileage
  - Upload invoice (optional)
- Click "Save"

### 4. View Dashboard

The dashboard shows:
- **Total Vehicles**: Count of your vehicles
- **Safe Renewals**: Items due in more than 30 days (green)
- **Due Soon**: Items due within 30 days (yellow)
- **Overdue**: Items past due date (red)
- **Upcoming Renewals Table**: All renewals sorted by date
- **Recent Maintenance**: Last 5 service records

### 5. Email Notifications

- Automatic emails sent at:
  - 30 days before expiry
  - 7 days before expiry
  - 1 day before expiry
  - On expiry date
- Configure email settings in `.env`
- Disable notifications in Profile settings

---

## 🎨 Screenshots

### Login Page - Vehicle Themed
Beautiful gradient design with car icon, not the default Laravel authentication.

### Dashboard
Clean dashboard with statistics cards showing color-coded renewal status.

### Vehicle Management
Card-based vehicle listing with photos and status badges.

### Maintenance Records
Track all service history with dates and costs.

---

## 📁 Project Structure

```
d:\VT\
├── app/
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── CheckRenewals.php      # Renewal check command
│   │   └── Kernel.php                  # Task scheduler
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                   # Authentication controllers
│   │   │   └── DashboardController.php # Dashboard logic
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Livewire/
│   │   └── VehicleForm.php             # Livewire vehicle form
│   ├── Mail/
│   │   └── RenewalReminderMail.php     # Email template
│   └── Models/
│       ├── User.php
│       ├── Vehicle.php
│       ├── MaintenanceRecord.php
│       └── Notification.php
├── database/
│   └── migrations/                      # Database migrations
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── auth.blade.php          # Auth layout (vehicle themed)
│   │   │   └── app.blade.php           # Main app layout
│   │   ├── auth/
│   │   │   ├── login.blade.php         # Custom login page
│   │   │   └── register.blade.php      # Custom register page
│   │   ├── dashboard.blade.php         # Dashboard view
│   │   ├── vehicles/                   # Vehicle views
│   │   ├── emails/
│   │   │   └── renewal-reminder.blade.php
│   │   └── livewire/
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                          # Web routes
│   └── console.php                      # Console routes
├── .env.example                         # Environment template
├── composer.json                        # PHP dependencies
├── package.json                         # JavaScript dependencies
└── README.md                            # This file
```

---

## 🔧 Configuration

### Email Configuration (Gmail)

1. Enable 2-factor authentication on your Gmail account
2. Generate an App Password:
   - Go to Google Account Settings
   - Security > 2-Step Verification > App Passwords
   - Generate password for "Mail"
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-character-app-password
MAIL_ENCRYPTION=tls
```

### Queue Configuration

For production, use database queue:

```env
QUEUE_CONNECTION=database
```

Run queue worker:

```bash
php artisan queue:work
```

---

## 🎯 Key Features Highlights

### ✅ Custom Vehicle-Themed UI
- NOT using default Laravel Breeze/Jetstream
- Beautiful gradient backgrounds
- Car-themed icons and imagery
- Professional Bootstrap 5 design

### ✅ Color-Coded Alert System
- 🟢 **Green (Safe)**: More than 30 days remaining
- 🟡 **Yellow (Due Soon)**: 30 days or less
- 🔴 **Red (Overdue)**: Past due date

### ✅ Automated Email Reminders
- Scheduled daily checks
- Multi-reminder system (30, 7, 1, 0 days)
- Professional email templates
- Queue-based sending

### ✅ Comprehensive Tracking
- Multiple vehicles per user
- Detailed maintenance history
- Cost tracking
- Document uploads (photos, invoices)

---

## 🐛 Troubleshooting

### Issue: Emails not sending

**Solution:**
- Check `.env` mail configuration
- Test with `php artisan tinker`:
  ```php
  Mail::raw('Test', function($msg) {
      $msg->to('test@example.com')->subject('Test');
  });
  ```
- Check queue worker is running

### Issue: Scheduler not running

**Solution:**
- Verify cron job is set up
- Test manually: `php artisan renewals:check`
- Check logs: `storage/logs/laravel.log`

### Issue: Images not uploading

**Solution:**
- Create storage link: `php artisan storage:link`
- Check permissions: `chmod -R 755 storage`
- Verify `public/storage` directory exists

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Developer Notes

### Adding New Features

1. Create migration: `php artisan make:migration create_table_name`
2. Create model: `php artisan make:model ModelName`
3. Create controller: `php artisan make:controller ControllerName`
4. Create Livewire component: `php artisan make:livewire ComponentName`

### Running Tests

```bash
php artisan test
```

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📞 Support

For issues and questions:
- Create an issue in the repository
- Check existing documentation
- Review Laravel and Livewire official docs

---

## 🎉 Getting Started Checklist

- [ ] Install Composer dependencies
- [ ] Install NPM dependencies
- [ ] Configure `.env` file
- [ ] Create database
- [ ] Run migrations
- [ ] Generate application key
- [ ] Configure email settings
- [ ] Set up cron job for scheduler
- [ ] Start development server
- [ ] Register first account
- [ ] Add first vehicle
- [ ] Test email notifications

---

**Happy Vehicle Tracking! 🚗💨**
