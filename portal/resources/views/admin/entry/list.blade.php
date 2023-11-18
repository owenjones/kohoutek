@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container">
      @include('components.alerts')

      <nav class="uk-navbar-container uk-navbar-transparent" uk-navbar>
        <div class="uk-navbar-center">
          <ul class="uk-navbar-nav uk-flex uk-flex-wrap">
            <li><a href="{{ route('admin.entries') }}">All</a></li>
            <li><a href="{{ route('admin.entries') }}/verified">Verified</a></li>
            <li><a href="{{ route('admin.entries') }}/unverified">Unverified</a></li>
          </ul>
        </div>
      </nav>

      <table class="uk-table uk-table-responsive uk-table-striped uk-table-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Group/Unit (Troop)</th>
            <th class="uk-table-shrink">District/Division</th>
            <th class="uk-table-shrink">County</th>
            <th class="uk-table-shrink">Organisation</th>
            <th class="uk-table-shrink">Verified</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($entries as $entry)
            <tr>
              <td class="uk-text-small uk-table-link">
                <a href="{{ route('admin.entry.view', ['id' => $entry->id]) }}">{{ $entry->id }}</a>
              </td>
              <td>{{ $entry->name }}</td>
              <td>{{ $entry->district()->name }}</td>
              <td>{{ $entry->district()->county()->name }}</td>
              <td>{{ $entry->district()->county()->type }}</td>
              <td>
                @if ($entry->verified)
                <span class="uk-text-success" uk-icon="check"></span>@else<span class="uk-text-danger"
                    uk-icon="close"></span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
