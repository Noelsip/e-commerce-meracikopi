<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Meracikopi' }}</title>

    <!-- Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #34231c;
            --secondary: #CA7842;
            --accent: #B2CD9C;
            --background: #1a1410;
        }
    </style>
</head>

<body style="font-family: 'Poppins', sans-serif; background-color: #1a1410; margin: 0;">
    <!-- Navbar -->
    @include('components.customer.navbar')

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.customer.footer')
</body>

</html>