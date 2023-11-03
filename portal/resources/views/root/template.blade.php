<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:title" content="Kohoutek" />
    <meta property="og:description"
        content="Kohoutek is a competition and activity day for Scouts in Avon County, and Guides in Bristol and South Gloucestershire, and Somerset North counties, organised by students from the University of Bristol Guides and Scouts society (UOBGAS)." />
    <meta property="og:image" content="{{ asset('img/og.jpg') }}" />
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('css/main.min.css') }}" />
    <script src="{{ asset('js/uikit.min.js') }}" defer></script>
    <script src="{{ asset('js/uikit-icons.min.js') }}" defer></script>
    <script src="{{ asset('js/sign-up.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
    @stack('head-js')
    <title>Kohoutek</title>
</head>

<body class="uk-background-secondary">
    @yield('body')
    @stack('body-js')
</body>

</html>
