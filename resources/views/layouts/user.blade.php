<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>



    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto+Slab:wght@300&family=Ubuntu:wght@300&display=swap" rel="stylesheet">

    <style>
        html body{
            font-family: 'Ubuntu', sans-serif;
        }
    </style>
</head>
<body>
    <div id="app">
        @auth
            <user-navbar prop-user='@json(auth()->user())'></user-navbar>
        @else
            <user-navbar prop-user=''></user-navbar>
        @endauth

        <div>
            @yield('content')
        </div>


    </div>
</body>
</html>
