<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Container Dispatch') }}</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- OR if you have a compiled CSS file -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
</head>

<body class="antialiased" style="font-family: 'Roboto', sans-serif;">
    <div class="min-h-screen bg-gray-50">
        @yield('content')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </div>
</body>

</html>
