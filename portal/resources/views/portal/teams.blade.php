@extends('portal.template')
@section('body')
  <div class="uk-width-1-1@m">
    <h3 class="uk-text-center uk-text-left@m">Teams</h3>
    <table class="uk-table uk-table-resonsive uk-table-middle uk-table-striped">
      <thead>
        <th class="uk-table-shrink">Code</th>
        <th class="uk-table-expand">Name</th>
        <th></th>
      </thead>
      <tbody>
        @foreach ($entry->teams as $team)
          <tr>
            <td>{{ $team->code }}</td>
            <td>{{ $team->name }}</td>
            <td>Modify</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <p class="uk-margin uk-text-small">
      Words about one team, or button to add another.
    </p>
  </div>
@endsection
