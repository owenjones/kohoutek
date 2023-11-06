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

<body id="portal" class="uk-height-viewport uk-background-@yield('background', 'primary')">
  <div class="uk-section uk-section-primary uk-section-xsmall uk-text-center">
    <h2>{{ $entry->name }}</h2>
  </div>
  <div class="uk-section uk-section-default uk-section-xsmall uk-padding-remove-bottom">
    <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
      <div class="uk-navbar-center">
        <ul class="uk-navbar-nav uk-flex uk-flex-wrap">
          <li><a href="{{ route('portal') }}">Home</a></li>
          <li><a href="{{ route('portal.teams') }}">Teams</a></li>
          <li><a href="{{ route('portal') }}">Updates</a></li>
          <li><a href="{{ route('portal.logout') }}">Logout</a></li>
        </ul>
      </div>
    </nav>
  </div>
  <div class="uk-section uk-section-default uk-section-small">
    <div class="uk-container uk-container-medium">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-1-1 uk-text-center">
          @include('components.alerts')
        </div>
        @yield('body')
      </div>
    </div>
  </div>
  <div class="uk-section uk-section-primary uk-section-small">
    <div class="uk-container uk-container-small uk-text-center">
      <p class="uk-text-small">
        Kohoutek is organised by students and alumni from the University of Bristol, with support from the <a
          href="https://www.facebook.com/UoBGaS" target="_blank" rel="noreferrer noopener">University of Bristol
          Guides
          and Scouts (UOBGAS)</a> society, part of the <a href="https://ssago.org" target="_blank"
          rel="noreferrer noopener">Student Scout and Guide Organisation (SSAGO)</a>.
      </p>
      <p class="uk-text-small">
        More information about the organisation and history of the event can be found on the <a href="/#about">home
          page</a>.
      </p>
    </div>
  </div>
  @stack('body-js')
</body>

</html>
