
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

            @if(!session('flash') == null)
                <flash-item message="{{ session('flash') }}" notifier="snackbar"></flash-item>
            @endif

            @include('includes.navigation')

            <div class="columns m-t-20">
                <div class="column is-1">
                    @include('includes.manage.navigation')
                </div>
                <div class="column content m-r-20">
                    @yield('content')
                </div>
            </div>

        </div>
        @routes
        <script src="{{ mix('assets/js/app.js') }}" ></script>
        @yield('elixir')
    </body>
</html>
