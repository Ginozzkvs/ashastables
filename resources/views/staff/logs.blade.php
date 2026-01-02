<!DOCTYPE html>
<html>
<head>
    <title>Staff Activity Logs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body { font-family: Arial; padding: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        .ok { color: green; font-weight: bold; }
        .fail { color: red; font-weight: bold; }
    </style>
</head>
<body>
<div style="background:#333; padding:10px;">
    <a href="/" style="color:white; margin-right:10px;">Home</a>
    <a href="/staff/logs"
   style="
    display:block;
    padding:15px;
    margin:10px 0;
    background:#007bff;
    color:white;
    text-align:center;
    border-radius:8px;
    font-size:18px;
   ">
   📋 Staff Logs
</a>

</div>

<h2>📋 Staff Activity Logs</h2>

<table>
    <tr>
        <th>Time</th>
        <th>Staff</th>
        <th>Member</th>
        <th>Activity</th>
        <th>Status</th>
        <th>Message</th>
    </tr>

    @foreach ($logs as $log)
    <tr>
        <td>{{ $log->created_at }}</td>
        <td>{{ $log->staff->name ?? '-' }}</td>
        <td>{{ $log->member->name ?? '-' }}</td>
        <td>{{ $log->activity->name ?? '-' }}</td>
        <td class="{{ $log->success ? 'ok' : 'fail' }}">
            {{ $log->success ? 'SUCCESS' : 'FAILED' }}
        </td>
        <td>{{ $log->message }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
