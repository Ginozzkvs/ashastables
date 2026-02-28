<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASHA Stables - Luxury Resort Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-200 bg-gradient-to-br from-dark to-dark-card min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="container mx-auto px-4 py-8 flex flex-col md:flex-row justify-between items-center gap-6">
        <a href="#" class="flex items-center gap-3 text-2xl font-serif font-bold text-gold">
            <img src="{{ asset('images/ASHA_LOGO-1.png') }}" class="h-10 w-auto" alt="Logo">
            ASHA Stables
        </a>
        
        <ul class="flex gap-8">
            <li><a href="#features" class="text-gray-200 font-medium hover:text-gold transition-colors">Features</a></li>
            <li><a href="#about" class="text-gray-200 font-medium hover:text-gold transition-colors">About</a></li>
        </ul>

        <div class="flex gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-gold text-dark font-semibold py-2 px-6 rounded hover:bg-gold-light hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] hover:-translate-y-0.5 transition-all duration-300">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="border-2 border-gold text-gold font-semibold py-2 px-6 rounded hover:bg-gold-dim hover:-translate-y-0.5 transition-all duration-300">
                    Login
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col justify-center items-center text-center px-4 py-12 md:py-20 w-full max-w-6xl mx-auto">
        <h1 class="text-5xl md:text-7xl font-serif text-gold mb-6 leading-tight drop-shadow-lg">ASHA Stables</h1>
        <p class="text-2xl md:text-3xl text-gray-300 mb-4 font-light">Luxury Resort Management System</p>
        <p class="text-lg text-[#b0a77d] mb-12 max-w-2xl mx-auto">
            Advanced member management, activity tracking, and analytics for your resort
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center mb-20 w-full max-w-2xl">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-gold text-dark font-bold text-lg py-4 px-8 rounded hover:bg-gold-light hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                    Go to Dashboard
                </a>
                @if(auth()->user() && (auth()->user()->role === 'staff' || auth()->user()->role === 'admin'))
                    <a href="{{ route('staff.scan') }}" class="border-2 border-gold text-gold font-bold text-lg py-4 px-8 rounded hover:bg-gold-dim hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                        Staff Scan
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="border-2 border-gold text-gold font-bold text-lg py-4 px-8 rounded hover:bg-gold-dim hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                    Admin Login
                </a>
                <a href="{{ route('staff.scan') }}" class="bg-gold text-dark font-bold text-lg py-4 px-8 rounded hover:bg-gold-light hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                    Staff Scan
                </a>
            @endauth
        </div>

        <!-- Features Section -->
        <div id="features" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
            @php
                $features = [
                    ['title' => 'Member Management', 'desc' => 'Complete member lifecycle management with NFC card integration and activity tracking'],
                    ['title' => 'NFC Check-In', 'desc' => 'Seamless NFC card scanning for quick and efficient member check-ins'],
                    ['title' => 'Advanced Analytics', 'desc' => 'Real-time dashboard with revenue tracking, member trends, and activity insights'],
                    ['title' => 'Membership Renewal', 'desc' => 'Automatic expiration tracking and streamlined renewal process'],
                    ['title' => 'Reports & Exports', 'desc' => 'Comprehensive reports with CSV exports for revenue, members, and activities'],
                    ['title' => 'Role-Based Access', 'desc' => 'Secure admin and staff roles with different permission levels'],
                ];
            @endphp
            
            @foreach($features as $feature)
            <div class="bg-dark-card border border-gold p-8 rounded-lg text-center hover:shadow-[0_8px_20px_rgba(212,175,55,0.2)] hover:-translate-y-2 transition-all duration-300 group">
                <h3 class="text-gold text-2xl font-serif mb-4 group-hover:text-gold-light transition-colors">{{ $feature['title'] }}</h3>
                <p class="text-gray-400 group-hover:text-gray-200 transition-colors">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-20 py-8 border-t border-gold/20 text-center text-gray-500 text-sm">
        <p>&copy; 2026 ASHA Stables. Luxury Resort Management System. All rights reserved.</p>
    </footer>
</body>
</html>
