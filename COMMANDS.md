# 🛠️ Common Commands - Smart Vehicle Tracker

## 📦 Installation Commands

```powershell
# Run automated installation
.\install.ps1

# Or manually:
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
php artisan storage:link
```

---

## 🗄️ Database Commands

```powershell
# Run all migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (WARNING: deletes all data)
php artisan migrate:fresh

# Check migration status
php artisan migrate:status

# Create new migration
php artisan make:migration create_tablename_table
```

---

## 🚀 Development Server

```powershell
# Start Laravel server (Terminal 1)
php artisan serve

# Compile assets (Terminal 2)
npm run dev

# Or compile for production
npm run build
```

**Access at**: http://localhost:8000

---

## 🔄 Cache Commands

```powershell
# Clear all caches
php artisan optimize:clear

# Or individually:
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📧 Email & Queue Commands

```powershell
# Test renewal check manually
php artisan renewals:check

# Work queue jobs
php artisan queue:work

# Listen to queue (auto-restart)
php artisan queue:listen

# Clear failed jobs
php artisan queue:flush

# Retry failed jobs
php artisan queue:retry all
```

---

## ⏰ Scheduler Commands

```powershell
# Run scheduler manually (for testing)
php artisan schedule:run

# List scheduled tasks
php artisan schedule:list

# Run specific command
php artisan renewals:check
```

**Production Setup**:
- Windows: Task Scheduler to run `php artisan schedule:run` every minute
- Linux/Mac: Cron job `* * * * * cd /path && php artisan schedule:run`

---

## 🧪 Testing Commands

```powershell
# Run tests
php artisan test

# With coverage
php artisan test --coverage

# Specific test
php artisan test --filter=TestName
```

---

## 🎨 Livewire Commands

```powershell
# Create new Livewire component
php artisan make:livewire ComponentName

# Publish Livewire config
php artisan livewire:publish --config

# Clear Livewire cache
php artisan livewire:delete-uploaded-files
```

---

## 🔨 Make Commands

```powershell
# Create model
php artisan make:model ModelName

# Model with migration
php artisan make:model ModelName -m

# Controller
php artisan make:controller ControllerName

# Request
php artisan make:request RequestName

# Mail class
php artisan make:mail MailName

# Command
php artisan make:command CommandName

# Middleware
php artisan make:middleware MiddlewareName

# Seeder
php artisan make:seeder SeederName
```

---

## 🔍 Debugging Commands

```powershell
# Enter tinker (REPL)
php artisan tinker

# Show routes
php artisan route:list

# Show environment
php artisan env

# Database info
php artisan db:show

# Test database connection
php artisan db:monitor
```

---

## 🧹 Maintenance Commands

```powershell
# Put app in maintenance mode
php artisan down

# Bring app back up
php artisan up

# With secret to bypass
php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"
# Access: yoursite.com/1630542a-246b-4b66-afa1-dd72a4c43515
```

---

## 📊 Database Seeding

```powershell
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

---

## 🔐 User Management (via Tinker)

```powershell
php artisan tinker
```

Then in tinker:

```php
// Create user
$user = new App\Models\User();
$user->name = 'John Doe';
$user->email = 'john@example.com';
$user->password = Hash::make('password123');
$user->save();

// Find user
$user = App\Models\User::where('email', 'john@example.com')->first();

// Update user
$user->email_notifications = true;
$user->save();

// Delete user
$user->delete();

// Count users
App\Models\User::count();

// Get all vehicles
App\Models\Vehicle::all();
```

---

## 📧 Test Email (via Tinker)

```powershell
php artisan tinker
```

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\RenewalReminderMail;
use App\Models\User;

$user = User::first();
$reminders = [
    [
        'type' => 'Vehicle License',
        'vehicle' => 'Toyota Camry',
        'vehicle_number' => 'ABC-1234',
        'due_date' => '2025-01-15',
        'days_left' => 7,
    ]
];

Mail::to($user->email)->send(new RenewalReminderMail($user, $reminders));
```

---

## 🔄 Git Commands

```powershell
# Initialize repository
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit"

# Add remote
git remote add origin https://github.com/username/repo.git

# Push
git push -u origin main
```

---

## 📦 Composer Commands

```powershell
# Install dependencies
composer install

# Update dependencies
composer update

# Install specific package
composer require vendor/package

# Remove package
composer remove vendor/package

# Dump autoload
composer dump-autoload

# Show installed packages
composer show
```

---

## 🎨 NPM Commands

```powershell
# Install dependencies
npm install

# Update dependencies
npm update

# Development build (watch mode)
npm run dev

# Production build
npm run build

# Install specific package
npm install package-name

# Remove package
npm uninstall package-name
```

---

## 🔍 Logs & Debugging

```powershell
# View logs (real-time)
Get-Content storage\logs\laravel.log -Wait -Tail 50

# Clear log file
Clear-Content storage\logs\laravel.log

# View error log only
Select-String -Path storage\logs\laravel.log -Pattern "ERROR"
```

---

## 🚀 Quick Start Workflow

```powershell
# 1. Fresh setup
.\install.ps1
php artisan migrate

# 2. Start development
# Terminal 1:
php artisan serve

# Terminal 2:
npm run dev

# 3. Test renewals
php artisan renewals:check

# 4. Access app
# Open: http://localhost:8000
```

---

## 🆘 Troubleshooting Commands

```powershell
# Permission errors (storage)
# Windows: No action needed
# Linux/Mac: chmod -R 775 storage bootstrap/cache

# Clear everything
php artisan optimize:clear
composer dump-autoload
php artisan config:clear

# Regenerate key
php artisan key:generate

# Fix storage link
php artisan storage:link

# Database connection test
php artisan db:show

# Check routes
php artisan route:list
```

---

## 📊 Production Optimization

```powershell
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# Set permissions
# chmod -R 755 storage bootstrap/cache (Linux/Mac)
```

---

## 🎯 Most Used Commands

### Daily Development
```powershell
php artisan serve          # Start server
npm run dev               # Watch assets
php artisan migrate       # Run migrations
php artisan optimize:clear # Clear caches
```

### Testing Features
```powershell
php artisan renewals:check    # Test email reminders
php artisan tinker            # Test code interactively
php artisan queue:work        # Process jobs
```

### Deployment
```powershell
composer install --no-dev     # Install dependencies
npm run build                 # Build assets
php artisan migrate --force   # Run migrations
php artisan config:cache      # Cache config
```

---

**Keep this file handy for quick reference! 🚀**
