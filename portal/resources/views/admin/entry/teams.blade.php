@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h4 class="uk-card-title">Teams</h4>
    <table class="uk-table uk-table-resonsive uk-table-middle uk-table-striped">
      <thead>
        <th class="uk-table-shrink">Code</th>
        <th class="uk-table-expand">Name</th>
        <th class="uk-table-shrink">Payment Received</th>
        <th></th>
      </thead>
      <tbody>
        @foreach ($entry->teams as $team)
          <tr>
            <td>{{ $team->code }}</td>
            <td>{{ $team->name }}</td>
            <td class="uk-text-center">
              @if ($team->payment_received)
                <span class="uk-text-success" uk-icon="check"></span>
              @else
                <span class="uk-text-danger" uk-icon="close"></span>
              @endif
            </td>
            <td class="uk-text-right uk-table-shrink">
              <form method="post" action="{{ route('admin.entry.teams.mark-paid', ['id' => $entry->id]) }}">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}" />
                <input type="submit" class="uk-button uk-button-default uk-button-small" value="Mark paid" />
              </form>
            </td>
            <td class="uk-text-right uk-table-shrink">
              <form method="post" action="{{ route('admin.entry.teams.delete', ['id' => $entry->id]) }}">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}" />
                <input type="submit" class="uk-button uk-button-danger uk-button-small" value="Delete" />
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <h5>Add New Team</h5>
    <form method="post" action="{{ route('admin.entry.teams.add', ['id' => $entry->id]) }}">
      @csrf
      <input type="submit" class="uk-button uk-button-default" value="Add new team" />
    </form>
  </div>
@endsection
