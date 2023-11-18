@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h4 class="uk-card-title">Teams</h4>
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
            <td>{{ $team->name }}</td>
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

    <h5>Add New Team</h5>
    <p>Form to add a new team to the entry</p>
  </div>
@endsection
