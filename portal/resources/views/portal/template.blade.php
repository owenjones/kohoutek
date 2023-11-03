<!doctype html>
<html lang="en" class="uk-height-viewport">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/main.min.css') }}" />
    <script src="{{ asset('js/uikit.min.js') }}" defer></script>
    <script src="{{ asset('js/uikit-icons.min.js') }}" defer></script>
    @stack('head-js')
    <title>Kohoutek Team Portal</title>
</head>

<body id="portal" class="uk-height-viewport uk-background-@yield('background', 'secondary')">
    @yield('body')
    @stack('body-js')
</body>

</html>
