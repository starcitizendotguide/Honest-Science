<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} - CONTENT</title>

        <link rel="stylesheet" href="{{ mix('assets/css/manage_app.css') }}">

    </head>
    <body>
        <div id="app">
            @include('includes.navigation')
            @include('includes.manage.navigation')

            @yield('content')
        </div>
        <script src="{{ mix('assets/js/app.js') }}" ></script>
    </body>
    @include('includes.footer')
</html>