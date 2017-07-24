<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <link rel="stylesheet" href="{{ mix('assets/css/app.css') }}">

    </head>
    <body>

        @if (session('status'))
            {{--
            $request->session()->flash('status', 'Task was successful!');
             --}}
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div id="app">
            @include('includes.navigation')
            @yield('content')
        </div>
        <script src="{{ mix('assets/js/app.js') }}"></script>
        @yield('elixir')
    </body>
    {{-- @TODO needs to be fixed
     @include('includes.footer')--}}
</html>
