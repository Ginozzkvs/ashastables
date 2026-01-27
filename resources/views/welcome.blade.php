<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASHA Stables - Luxury Resort Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f1419 0%, #1a1f2e 100%);
            color: #e0e0e0;
            line-height: 1.6;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cormorant Garamond', serif;
            letter-spacing: -1px;
            font-weight: 600;
        }

        a {
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #d4af37;
            font-size: 1.8rem;
            font-weight: 700;
            font-family: 'Cormorant Garamond', serif;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: #e0e0e0;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #d4af37;
        }

        .nav-auth {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-primary {
            background: #d4af37;
            color: #0f1419;
        }

        .btn-primary:hover {
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: #d4af37;
            border: 2px solid #d4af37;
        }

        .btn-secondary:hover {
            background: rgba(212, 175, 55, 0.1);
            transform: translateY(-2px);
        }

        .hero {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .hero h1 {
            font-size: 4rem;
            color: #d4af37;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.3rem;
            color: #9ca3af;
            margin-bottom: 2rem;
            max-width: 600px;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 4rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 4rem;
            width: 100%;
        }

        .feature-card {
            background: #1a1f2e;
            border: 1px solid #d4af37;
            padding: 2rem;
            border-radius: 0.5rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            color: #d4af37;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .feature-card p {
            color: #9ca3af;
            font-size: 0.95rem;
        }

        .footer {
            margin-top: 6rem;
            padding: 2rem;
            border-top: 1px solid rgba(212, 175, 55, 0.2);
            text-align: center;
            color: #6b7280;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .nav-links {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .hero {
                min-height: auto;
                padding: 1.5rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .btn {
                width: 100%;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <div class="navbar">
        <a href="#" class="logo">ASHA Stables</a>
        
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#about">About</a></li>
        </ul>

        <div class="nav-auth">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
            @endauth
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <h1>ASHA Stables</h1>
        <p>Luxury Resort Management System</p>
        <p style="font-size: 1.1rem; color: #b0a77d; margin-bottom: 3rem;">Advanced member management, activity tracking, and analytics for your resort</p>

        <div class="hero-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                @if(auth()->user() && (auth()->user()->role === 'staff' || auth()->user()->role === 'admin'))
                    <a href="{{ route('staff.scan') }}" class="btn btn-primary">Staff Scan</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-secondary">Admin Login</a>
                <a href="{{ route('staff.scan') }}" class="btn btn-primary">Staff Scan</a>
            @endauth
        </div>

        <!-- Features Section -->
        <div id="features" class="features">
            <div class="feature-card">
                <h3>Member Management</h3>
                <p>Complete member lifecycle management with NFC card integration and activity tracking</p>
            </div>

            <div class="feature-card">
                <h3>NFC Check-In</h3>
                <p>Seamless NFC card scanning for quick and efficient member check-ins</p>
            </div>

            <div class="feature-card">
                <h3>Advanced Analytics</h3>
                <p>Real-time dashboard with revenue tracking, member trends, and activity insights</p>
            </div>

            <div class="feature-card">
                <h3>Membership Renewal</h3>
                <p>Automatic expiration tracking and streamlined renewal process</p>
            </div>

            <div class="feature-card">
                <h3>Reports & Exports</h3>
                <p>Comprehensive reports with CSV exports for revenue, members, and activities</p>
            </div>

            <div class="feature-card">
                <h3>Role-Based Access</h3>
                <p>Secure admin and staff roles with different permission levels</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2026 ASHA Stables. Luxury Resort Management System. All rights reserved.</p>
    </div>
</body>
</html>
