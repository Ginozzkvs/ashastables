<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f6f8; }
        .card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 15px; }
        h1 { color: #333; }
        p { color: #666; font-size: 18px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Admin Dashboard</h1>
        <p>Welcome, {{ auth()->user()->name }}</p>
    </div>
</body>
</html>
