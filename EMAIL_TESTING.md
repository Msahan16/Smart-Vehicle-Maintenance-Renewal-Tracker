# Email Notification Testing Guide

## ✅ Email System is Working!

The email notification system has been successfully tested and is functional.

---

## Test Email Command

### Quick Test (Logs Only)
```powershell
# Emails will be saved to storage/logs/laravel.log
php artisan email:test
```

### Test with Your Email
```powershell
# Replace with your actual email address
php artisan email:test your-email@gmail.com
```

---

## Email Configuration Options

### Option 1: Log Driver (Currently Active - NO SETUP NEEDED)
**Best for testing without email server**

Your `.env` is currently set to:
```env
MAIL_MAILER=log
```

**How to check:**
```powershell
Get-Content storage\logs\laravel.log -Tail 100 | Select-String -Pattern "Subject:"
```

---

### Option 2: Gmail SMTP (For Real Emails)

**Step 1: Get Gmail App Password**
1. Go to https://myaccount.google.com/security
2. Enable 2-Step Verification
3. Go to https://myaccount.google.com/apppasswords
4. Generate an App Password for "Mail"
5. Copy the 16-character password

**Step 2: Update `.env` file:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Smart Vehicle Tracker"
```

**Step 3: Clear config cache:**
```powershell
php artisan config:clear
```

**Step 4: Test:**
```powershell
php artisan email:test your-email@gmail.com
```

---

### Option 3: Mailtrap (For Development)

**Free email testing service (no real emails sent)**

1. Sign up at https://mailtrap.io
2. Get your credentials from the inbox

**Update `.env`:**
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@vehicletracker.com
MAIL_FROM_NAME="Smart Vehicle Tracker"
```

---

## Automated Renewal Checks

### Manual Check
```powershell
php artisan renewals:check
```

This command:
- Checks all vehicles for upcoming renewals
- Sends emails for renewals due in: **30, 7, 1, or 0 days**
- Only sends to users with `email_notifications = 1`

### Automated Daily Schedule

The command runs automatically **every day at midnight** via Laravel's task scheduler.

**To activate the scheduler:**

#### Windows Task Scheduler:
1. Open Task Scheduler
2. Create Basic Task
3. Name: "Vehicle Tracker Scheduler"
4. Trigger: Daily at 12:00 AM
5. Action: Start a program
6. Program: `C:\path\to\php.exe`
7. Arguments: `artisan schedule:run`
8. Start in: `D:\VT`

#### Or run manually in terminal (keeps running):
```powershell
# Run this in a separate terminal window
php artisan schedule:work
```

---

## Email Template Preview

The renewal reminder email includes:

- **Beautiful gradient header** (purple/blue)
- **User's name** personalization
- **Each renewal** with:
  - Type (Vehicle License, Insurance, Emission Test, Driver License)
  - Vehicle details
  - Due date
  - Days remaining
  - Color-coded urgency (red for ≤7 days, orange for ≤30 days)
- **Action button** to visit dashboard
- **Professional footer** with app info

---

## Testing with Real Data

### 1. Add a vehicle with upcoming expiry:
```sql
-- In your MySQL database
UPDATE vehicles 
SET insurance_expiry = DATE_ADD(CURDATE(), INTERVAL 7 DAYS)
WHERE id = 1;
```

### 2. Enable email notifications in profile:
- Go to http://localhost:8000/profile
- Check "Receive email reminders"
- Save

### 3. Run renewal check:
```powershell
php artisan renewals:check
```

### 4. Check logs or email:
```powershell
# For log driver
Get-Content storage\logs\laravel.log -Tail 200
```

---

## Troubleshooting

### "Connection could not be established"
- **Gmail**: Make sure you're using App Password, not regular password
- **Firewall**: Check if port 587 is blocked
- **2FA**: Gmail requires 2-Step Verification enabled

### No emails sent
1. Check user has `email_notifications = 1`:
   ```sql
   SELECT id, email, email_notifications FROM users;
   ```

2. Check vehicle has upcoming expiry:
   ```sql
   SELECT vehicle_number, insurance_expiry, 
          DATEDIFF(insurance_expiry, CURDATE()) as days_left
   FROM vehicles;
   ```

3. Run with verbose output:
   ```powershell
   php artisan renewals:check -v
   ```

### Email appears in spam
- This is normal for development emails
- Check your spam/junk folder
- For production, use a verified email domain

---

## Quick Reference

| Command | Purpose |
|---------|---------|
| `php artisan email:test` | Send test email to default address |
| `php artisan email:test email@example.com` | Send test email to specific address |
| `php artisan renewals:check` | Check renewals and send notifications |
| `php artisan schedule:work` | Run scheduler continuously |
| `php artisan config:clear` | Clear config cache after .env changes |
| `php artisan queue:work` | Process queued emails (if using queue) |

---

## Current Status

✅ **Email system is working**
✅ **Test command created**
✅ **Log driver configured** (no external setup needed)
✅ **Renewal check command functional**
✅ **Daily scheduler configured**

**Next Steps:**
1. Test with your real email by configuring Gmail SMTP (optional)
2. Add vehicles with upcoming expiry dates
3. Enable email notifications in your profile
4. Run `php artisan renewals:check` to test

---

## Email Notification Rules

Emails are sent when:
- **30 days before** expiry (first warning)
- **7 days before** expiry (second warning)
- **1 day before** expiry (urgent warning)
- **On expiry day** (final warning)

Each user receives **one email per day** with all their pending renewals.
