<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>SISAHY TRANSPORT-สี่สหายขนส่ง</title>
    <meta name='description' content='Transportation management'>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4299E1" />

    <link rel="canonical" href="https://app.sisahygo.online/" />
    @yield('ogmeta')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Kodchasan:400,500,600,700" rel="stylesheet">

    <script src="{{ mix('/js/app.js') }}" defer></script>
    @if (App::environment('production', 'staging'))
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('/sw.js').then(function(registration) {
                        // Registration was successful
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, function(err) {
                        // registration failed :(
                        console.log('ServiceWorker registration failed: ', err);
                    });
                });
            }
        </script>
    @endif


    <link rel="manifest" href="/manifest.json">


    <!-- Scripts -->
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>


    @if (!auth()->guest())
        <script>
            window.Laravel.userId = <?php echo auth()->user()->id; ?>
        </script>
    @endif
    <link rel="shortcut icon" href="<?php echo asset('images/icons/favicon.png'); ?>">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    @stack('scripts')


    @livewireStyles
</head>

<body class="font-sans leading-normal tracking-normal antialiased">

    <div>

        @yield('nav')

        @yield('sghome')

        @yield('content')
        @yield('footer')
    </div>

    @livewireScripts

</body>

</html>
