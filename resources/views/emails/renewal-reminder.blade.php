<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            line-height: 1.6;
            color: #334155;
            margin: 0;
            padding: 24px 12px;
            background: #f1f5f9;
        }

        .email-wrapper {
            max-width: 680px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #dbe5ef;
            border-radius: 14px;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(90deg, #134e7f 0%, #1e3a5f 100%);
            color: #ffffff;
            padding: 28px 24px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 30px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .header p {
            margin: 10px 0 0;
            font-size: 14px;
            color: #dbeafe;
        }

        .content {
            padding: 28px 24px 24px;
            background: #f8fafc;
        }

        .intro {
            margin: 0 0 16px;
            font-size: 16px;
            color: #1f2937;
        }

        .intro-sub {
            margin: 0 0 18px;
            color: #475569;
        }

        .reminder-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #1e3a5f;
            padding: 16px;
            margin: 0 0 14px;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .reminder-title {
            margin: 0 0 8px;
            color: #0f172a;
            font-size: 18px;
            font-weight: 700;
        }

        .meta {
            margin: 0;
            color: #334155;
            font-size: 15px;
        }

        .meta strong {
            color: #1f2937;
        }

        .urgent {
            border-left-color: #ef4444;
            background: #fff7f7;
        }

        .warning {
            border-left-color: #f59e0b;
            background: #fffbeb;
        }

        .due {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
        }

        .due-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .due-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .action-note {
            margin: 18px 0 0;
            color: #1f2937;
            font-weight: 600;
        }

        .btn-wrap {
            text-align: center;
            margin-top: 22px;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            background: #10b981;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
        }

        .footer {
            text-align: center;
            margin-top: 0;
            padding: 18px 24px 22px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 12px;
        }

        .footer p {
            margin: 4px 0;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 12px 8px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 20px 16px;
            }

            .footer {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>Vehicle Renewal Reminder</h1>
            <p>Smart Vehicle Tracker</p>
        </div>

        <div class="content">
            <p class="intro">Hello {{ $user->name }},</p>

            <p class="intro-sub">This is a reminder that you have upcoming renewals that need attention.</p>

            @foreach($reminders as $reminder)
                <div class="reminder-box {{ $reminder['days_left'] <= 7 ? 'urgent' : ($reminder['days_left'] <= 30 ? 'warning' : '') }}">
                    <h3 class="reminder-title">{{ $reminder['type'] }}</h3>
                    <p class="meta">
                        <strong>Vehicle:</strong> {{ $reminder['vehicle'] }}<br>
                        <strong>Number:</strong> {{ $reminder['vehicle_number'] }}<br>
                        <strong>Due Date:</strong> {{ $reminder['due_date'] }}<br>
                        @if($reminder['days_left'] == 0)
                            <span class="due due-danger">DUE TODAY</span>
                        @elseif($reminder['days_left'] == 1)
                            <span class="due due-danger">Due in 1 day</span>
                        @elseif($reminder['days_left'] <= 7)
                            <span class="due due-danger">Due in {{ $reminder['days_left'] }} days</span>
                        @else
                            <span class="due due-warning">Due in {{ $reminder['days_left'] }} days</span>
                        @endif
                    </p>
                </div>
            @endforeach

            <p class="action-note">Please renew these items before they expire.</p>

            <div class="btn-wrap">
                <a href="{{ url('/dashboard') }}" class="btn">View Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated reminder from Smart Vehicle Tracker.</p>
            <p>You're receiving this because you have email notifications enabled.</p>
            <p>To manage notification settings, visit your profile page.</p>
        </div>
    </div>
</body>
</html>
