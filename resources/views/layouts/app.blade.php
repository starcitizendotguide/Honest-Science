<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

        <!-- <script src="http:// \Config::get('custom.disqus.shortname') .disqus.com/embed.js" charset="utf-8"></script> -->

    </head>
    <body class="grid">
        <div >
            <div id="app">
                @include('includes.navigation')
                @yield('content')
                @include('includes.footer')
            </div>
        </div>
        @routes
        <script src="{{ mix('assets/js/app.js') }}"></script>
        @yield('external')
    </body>
</html>
