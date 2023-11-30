@extends('portal.template')
@section('body')
  <div class="uk-width-1-2@m">
    <h3 class="uk-text-center uk-text-left@m">Entry</h3>
    <table class="uk-table uk-table-responsive uk-table-middle uk-table-striped">
      <tbody>
        <tr>
          <th class="uk-table-shrink">Entry ID</th>
          <td>{{ $entry->id }}</td>
        </tr>
        @if ($entry->troop)
          <tr>
            <th>Troop</th>
            <td>{{ $entry->troop }}</td>
          </tr>
        @endif
        <tr>
          <th>Group/Unit</th>
          <td>{{ $entry->group }}</td>
        </tr>
        <tr>
          <th>District/Division</td>
          <td>{{ $entry->district()->name }}</td>
        </tr>
        <tr>
          <th>County</th>
          <td>{{ $entry->district()->county()->name }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="uk-width-1-2@m">
    <h3 class="uk-text-center uk-text-left@m">Contact Information</h3>
    <table class="uk-table uk-table-responsive uk-table-middle uk-table-striped">
      <tbody>
        <tr>
          <th class="uk-table-shrink">Name</th>
          <td>{{ $entry->contact_name }}</td>
        </tr>
        <tr>
          <th>Email</th>
          <td>{{ $entry->contact_email }}</td>
        </tr>
      </tbody>
    </table>
    <p class="uk-margin uk-text-small">
      If you need to get in touch with the Kohoutek team we can be reached at <a
        href="mailto:contact@kohoutek.co.uk">contact@kohoutek.co.uk</a>.
    </p>
  </div>
@endsection
