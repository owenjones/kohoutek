@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container">
      <h2>Outstanding Teams</h2>
      @include('components.alerts')

      <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-center">
          <ul class="uk-navbar-nav uk-flex uk-flex-wrap">
            <li><a href="{{ route('admin.payments') }}">Received Payments</a></li>
            <li><a href="{{ route('admin.payments.outstanding') }}">Outstanding Teams</a></li>
          </ul>
        </div>
      </nav>

      <table class="uk-table uk-table-responsive uk-table-striped uk-table-middle">
        <thead>
          <tr>
            <th>Team Code</th>
            <th>Team Name</th>
            <th>Entry</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($teams as $team)
            <tr>
              <td>{{ $team->code }}</td>
              <td>{{ $team->name }}</td>
              <td><a href="{{ route('admin.entry.view', ['id' => $team->entry()->id]) }}">{{ $team->entry()->name }}
                  ({{ $team->entry()->id }})
                </a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
