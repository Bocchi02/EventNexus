<!DOCTYPE html>
<html>
<head>
    <title>Event Invitation</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f6f8; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; margin: 10px 5px; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-accept { background-color: #28a745; }
        .btn-decline { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="text-align: center; color: #696cff;">You're Invited!</h2>
        <p>Hi {{ $user->firstname }},</p>
        <p>You have been invited to <strong>{{ $event->title }}</strong>.</p>
        
        <div style="background: #f8f9fa; padding: 15px; border-left: 4px solid #696cff; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>📅 Date:</strong> {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y h:i A') }}</p>
            <p style="margin: 5px 0;"><strong>📍 Venue:</strong> {{ $event->venue }}</p>
        </div>

        <p style="text-align: center;">Will you be attending?</p>
        
        <div style="text-align: center;">
            <a href="{{ $acceptUrl }}" class="btn btn-accept">Accept</a>
            <a href="{{ $declineUrl }}" class="btn btn-decline">Decline</a>
        </div>
    </div>
</body>
</html>