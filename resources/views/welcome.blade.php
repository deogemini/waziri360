<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Waziri360</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased bg-gray-50 text-gray-800 font-sans">
    
    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-2xl font-bold text-blue-600">Waziri360</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#calendar" class="text-gray-600 hover:text-blue-600">Calendar</a>
                    <a href="#book" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Book Appointment</a>
                    <a href="/admin" class="text-sm text-gray-500 hover:text-gray-900">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    @livewire('home-page')

    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Waziri360. All rights reserved.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
