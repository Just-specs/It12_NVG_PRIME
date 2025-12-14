<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Container Dispatch') }}</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- OR if you have a compiled CSS file -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        @yield('content')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </div>
</body>

</html>