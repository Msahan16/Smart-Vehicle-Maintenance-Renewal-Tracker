<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .reminder-box {
            background: white;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .urgent {
            border-left-color: #ef4444;
        }
        .warning {
            border-left-color: #f59e0b;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚗 Vehicle Renewal Reminder</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $user->name }},</p>
        
        <p>This is a reminder that you have upcoming renewals that require your attention:</p>
        
        @foreach($reminders as $reminder)
            <div class="reminder-box {{ $reminder['days_left'] <= 7 ? 'urgent' : ($reminder['days_left'] <= 30 ? 'warning' : '') }}">
                <h3 style="margin: 0 0 10px 0;">{{ $reminder['type'] }}</h3>
                <p style="margin: 5px 0;">
                    <strong>Vehicle:</strong> {{ $reminder['vehicle'] }}<br>
                    <strong>Number:</strong> {{ $reminder['vehicle_number'] }}<br>
                    <strong>Due Date:</strong> {{ $reminder['due_date'] }}<br>
                    <strong>
                        @if($reminder['days_left'] == 0)
                            <span style="color: #ef4444;">⚠️ DUE TODAY!</span>
                        @elseif($reminder['days_left'] == 1)
                            <span style="color: #ef4444;">⚠️ Due in 1 day</span>
                        @elseif($reminder['days_left'] <= 7)
                            <span style="color: #ef4444;">⚠️ Due in {{ $reminder['days_left'] }} days</span>
                        @else
                            <span style="color: #f59e0b;">⚠️ Due in {{ $reminder['days_left'] }} days</span>
                        @endif
                    </strong>
                </p>
            </div>
        @endforeach
        
        <p style="margin-top: 20px;">
            <strong>Please take action to renew these items before they expire.</strong>
        </p>
        
        <center>
            <a href="{{ url('/dashboard') }}" class="btn">View Dashboard</a>
        </center>
    </div>
    
    <div class="footer">
        <p>This is an automated reminder from Smart Vehicle Tracker</p>
        <p>You're receiving this because you have email notifications enabled</p>
        <p>To manage your notification settings, visit your profile page</p>
    </div>
</body>
</html>
