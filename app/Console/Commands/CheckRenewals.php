<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Vehicle;
use App\Mail\RenewalReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckRenewals extends Command
{
    protected $signature = 'renewals:check';
    protected $description = 'Check for upcoming renewals and send email reminders';

    public function handle()
    {
        $this->info('Checking for upcoming renewals...');

        $users = User::with('vehicles')->where('email_notifications', true)->get();

        foreach ($users as $user) {
            $reminders = [];

            foreach ($user->vehicles as $vehicle) {
                // Check license expiry
                if ($vehicle->license_expiry) {
                    $daysLeft = now()->diffInDays($vehicle->license_expiry, false);
                    if (in_array($daysLeft, [30, 7, 1, 0])) {
                        $reminders[] = [
                            'type' => 'Vehicle License',
                            'vehicle' => $vehicle->brand . ' ' . $vehicle->model,
                            'vehicle_number' => $vehicle->vehicle_number,
                            'due_date' => $vehicle->license_expiry->format('M d, Y'),
                            'days_left' => $daysLeft,
                        ];
                    }
                }

                // Check insurance expiry
                if ($vehicle->insurance_expiry) {
                    $daysLeft = now()->diffInDays($vehicle->insurance_expiry, false);
                    if (in_array($daysLeft, [30, 7, 1, 0])) {
                        $reminders[] = [
                            'type' => 'Vehicle Insurance',
                            'vehicle' => $vehicle->brand . ' ' . $vehicle->model,
                            'vehicle_number' => $vehicle->vehicle_number,
                            'due_date' => $vehicle->insurance_expiry->format('M d, Y'),
                            'days_left' => $daysLeft,
                        ];
                    }
                }

                // Check emission test expiry
                if ($vehicle->emission_test_expiry) {
                    $daysLeft = now()->diffInDays($vehicle->emission_test_expiry, false);
                    if (in_array($daysLeft, [30, 7, 1, 0])) {
                        $reminders[] = [
                            'type' => 'Emission Test',
                            'vehicle' => $vehicle->brand . ' ' . $vehicle->model,
                            'vehicle_number' => $vehicle->vehicle_number,
                            'due_date' => $vehicle->emission_test_expiry->format('M d, Y'),
                            'days_left' => $daysLeft,
                        ];
                    }
                }
            }

            // Check driver license expiry
            if ($user->driver_license_expiry) {
                $daysLeft = now()->diffInDays($user->driver_license_expiry, false);
                if (in_array($daysLeft, [30, 7, 1, 0])) {
                    $reminders[] = [
                        'type' => 'Driver License',
                        'vehicle' => 'N/A',
                        'vehicle_number' => $user->driver_license_number ?? 'N/A',
                        'due_date' => $user->driver_license_expiry->format('M d, Y'),
                        'days_left' => $daysLeft,
                    ];
                }
            }

            // Send email if there are reminders
            if (count($reminders) > 0) {
                Mail::to($user->email)->send(new RenewalReminderMail($user, $reminders));
                $this->info("Sent reminder email to {$user->email}");
            }
        }

        $this->info('Renewal check completed!');
    }
}
