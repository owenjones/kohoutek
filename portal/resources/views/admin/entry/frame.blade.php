@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-small">
    <div class="uk-container uk-container-medium">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-1-1 uk-text-center">
          <h1>{{ $entry->name }}</h1>
          @include('components.alerts')
        </div>
        <div class="uk-card uk-card-secondary uk-card-body uk-width-1-5@m">
          <h4 class="uk-card-title">Actions</h4>
          <ul class="uk-nav uk-nav-default" uk-nav>
            <li><a href="{{ route('admin.entry.view', ['id' => $entry->id]) }}">Entry Information</a></li>
            <li><a href="{{ route('admin.entry.teams', ['id' => $entry->id]) }}">Teams</a></li>
            <li><a href="{{ route('admin.entry.payments', ['id' => $entry->id]) }}">Payments</a></li>
            <li><a href="{{ route('admin.entry.notes', ['id' => $entry->id]) }}">Notes</a></li>
            <li><a href="{{ route('admin.entry.contact', ['id' => $entry->id]) }}">Contact Entry</a></li>
            <li><a href="{{ route('admin.entry.resend', ['id' => $entry->id]) }}">Resend Portal Link</a></li>
            <li><a href="{{ route('admin.entry.cancel', ['id' => $entry->id]) }}">Cancel Entry</a></li>
          </ul>
        </div>
        <div class="uk-width-4-5@m uk-grid-item-match">
          <div uk-grid>
            @yield('module')
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
