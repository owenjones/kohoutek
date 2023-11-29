@extends('portal.template')
@section('body')
  <div class="uk-width-1-1@m">
    <h3 class="uk-text-center uk-text-left@m">Teams</h3>
    <table class="uk-table uk-table-resonsive uk-table-middle uk-table-striped">
      <thead>
        <th class="uk-table-shrink">Code</th>
        <th class="uk-table-expand">Name</th>
        <th class="uk-table-shrink">Payment Received</th>
      </thead>
      <tbody>
        @foreach ($entry->teams as $team)
          <tr>
            <td>{{ $team->code }}</td>
            <td>
              <div class="uk-grid">
                <div class="uk-width-2-3">{{ $team->name }}</div>
                <div class="uk-width-1-3 uk-text-right">
                  <a href="{{ route('portal.team.rename', ['id' => $team->id]) }}"
                    class="uk-button uk-button-primary uk-button-small">Change name</a>
                </div>
              </div>
            </td>
            <td class="uk-text-center">
              @if ($team->payment_received)
                <span class="uk-text-success" uk-icon="check"></span>
              @else
                <span class="uk-text-danger" uk-icon="close"></span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <p class="uk-margin uk-text-small">
      Words about one team, or button to add another.
    </p>

    {{-- <form method="post" action="{{ route('portal.team.add') }}">
      @csrf
      <input type="submit" class="uk-button uk-button-default" value="Add new team" />
    </form> --}}
  </div>
@endsection
