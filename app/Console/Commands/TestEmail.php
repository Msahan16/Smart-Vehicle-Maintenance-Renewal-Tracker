<?php

namespace App\Console\Commands;

use App\Mail\RenewalReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {email?}';
    protected $description = 'Send a test renewal reminder email';

    public function handle()
    {
        $email = $this->argument('email') ?? 'test@example.com';
        
        $this->info('Sending test email to: ' . $email);
        
        // Create a simple user object for testing
        $user = (object) ['name' => 'Test User'];
        
        $reminders = [
            [
                'type' => 'Vehicle License',
                'vehicle' => 'Toyota Camry',
                'vehicle_number' => 'ABC-1234',
                'brand' => 'Toyota',
                'model' => 'Camry',
                'due_date' => now()->addDays(7)->format('M d, Y'),
                'expiry_date' => now()->addDays(7)->format('M d, Y'),
                'days_left' => 7,
            ],
            [
                'type' => 'Insurance',
                'vehicle' => 'Toyota Camry',
                'vehicle_number' => 'ABC-1234',
                'brand' => 'Toyota',
                'model' => 'Camry',
                'due_date' => now()->addDays(30)->format('M d, Y'),
                'expiry_date' => now()->addDays(30)->format('M d, Y'),
                'days_left' => 30,
            ]
        ];
        
        try {
            Mail::to($email)->send(new RenewalReminderMail($user, $reminders));
            $this->info('✓ Test email sent successfully!');
            $this->info('Check your email or logs at: storage/logs/laravel.log');
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email: ' . $e->getMessage());
        }
    }
}
