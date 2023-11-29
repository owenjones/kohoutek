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
  <title>Kohoutek Admin</title>
</head>

<body id="portal" class="uk-height-viewport uk-background-default">
  <div class="uk-section uk-section-secondary uk-section-xsmall">
    <div class="uk-container uk-container-small uk-text-center">
      <h2>Kohoutek Admin</h2>
    </div>
  </div>
  <div class="uk-section uk-section-default uk-section-xsmall uk-padding-remove-bottom">
    <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
      <div class="uk-navbar-center">
        <ul class="uk-navbar-nav uk-flex uk-flex-wrap">
          <li><a href="{{ route('admin.index') }}">Home</a></li>
          <li><a href="{{ route('admin.entries') }}">Entries</a></li>
          <li><a href="{{ route('admin.index') }}">Scores</a></li>
          <li><a href="{{ route('admin.users') }}">Users</a></li>
          <li><a href="{{ route('admin.settings') }}">Settings</a></li>
          <li><a href="{{ route('admin.logout') }}">Logout</a></li>
        </ul>
      </div>
    </nav>
  </div>
  @yield('body')
  @stack('body-js')
</body>

</html>
