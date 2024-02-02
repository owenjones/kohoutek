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

<body id="portal" class="uk-height-viewport uk-background-secondary">
  <div class="uk-section uk-section-secondary uk-section-xsmall">
    <div class="uk-container">
      <div class="uk-flex-middle" uk-grid>
        <div class="uk-width-expand uk-width-1-1@s uk-text-center@s">
          <h2 class="uk-margin-remove">Kohoutek Admin</h2>
        </div>
        <div class="uk-hidden@s">
          <button class="uk-navbar-toggle" uk-toggle="target: #mobile-menu" type="button"
            uk-navbar-toggle-icon></button>
        </div>
      </div>
    </div>
  </div>

  <div class="uk-section uk-section-default uk-section-xsmall uk-padding-remove-bottom uk-visible@s">
    <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
      <div class="uk-navbar-center">
        <ul class="uk-navbar-nav uk-flex uk-flex-wrap">
          <li><a href="{{ route('admin.index') }}">Home</a></li>
          <li><a href="{{ route('admin.entries') }}">Entries</a></li>
          <li><a href="{{ route('admin.payments') }}">Payments</a></li>
          <li><a href="{{ route('admin.updates') }}">Updates</a></li>
          <li><a href="{{ route('admin.users') }}">Users</a></li>
          <li><a href="{{ route('admin.settings') }}">Settings</a></li>
          <li><a href="{{ route('admin.logout') }}">Logout</a></li>
        </ul>
      </div>
    </nav>
  </div>

  <div id="mobile-menu" uk-offcanvas>
    <div class="uk-offcanvas-bar">
      <button class="uk-offcanvas-close" type="button" uk-close></button>
      <ul class="uk-nav uk-nav-default">
        <li><a href="{{ route('admin.index') }}">Home</a></li>
        <li><a href="{{ route('admin.entries') }}">Entries</a></li>
        <li><a href="{{ route('admin.payments') }}">Payments</a></li>
        <li><a href="{{ route('admin.updates') }}">Updates</a></li>
        <li><a href="{{ route('admin.users') }}">Users</a></li>
        <li><a href="{{ route('admin.settings') }}">Settings</a></li>
        <li><a href="{{ route('admin.logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>

  <div class="uk-section uk-section-default">
    @yield('body')
  </div>

  <div class="uk-section uk-section-secondary uk-section-xsmall">
    <div class="uk-container uk-container-small uk-text-center">
      <p class="uk-text-small">
        &copy Kohoutek Competition {{ settings()->get('year') }}
      </p>
    </div>
  </div>
  @stack('body-js')
</body>

</html>
