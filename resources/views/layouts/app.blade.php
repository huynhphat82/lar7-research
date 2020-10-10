<html>
    <head>
        <title>App Name - @yield('title')</title>
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @show

        <div class="container" id="app">
            @yield('content')
        </div>

        @stack('scripts')
    </body>
</html>
