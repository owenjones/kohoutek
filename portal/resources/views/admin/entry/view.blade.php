@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <div uk-grid>
      <div class="uk-width-1-2@s">
        <h3>Entry Information</h3>
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
            <tr>
              <th>Organisation</th>
              <td>{{ $entry->district()->county()->type }}</td>
            </tr>
            <tr>
              <th>Signed Up</th>
              <td>{{ $entry->created_at }}</td>
            </tr>
            <tr>
              <th>Verified</th>
              <td>
                @if ($entry->verified)
                  <span class="uk-text-success" uk-icon="check"></span>
                @else
                  <span class="uk-text-danger uk-margin-right" uk-icon="close"></span>
                  <a class="uk-button uk-button-default"
                    href="{{ route('admin.entry.chase', ['id' => $entry->id]) }}">Chase</a>
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="uk-width-1-2@s">
        <h3>Contact Information</h3>
        <table class="uk-table uk-table-responsive uk-table-middle uk-table-striped">
          <tbody>
            <tr>
              <th class="uk-table-shrink">Name</th>
              <td>{{ $entry->contact_name }}</td>
            </tr>
            <tr>
              <th>Contact Email</th>
              <td>{{ $entry->contact_email }}</td>
            </tr>
          </tbody>
        </table>
        <p><a class="uk-button uk-button-default"
            href="{{ route('admin.entry.contact', ['id' => $entry->id]) }}">Contact</a></p>

        <h3>Portal Link</h3>
        <p>
          <input class="uk-input" type="text" value="{{ $entry->login_link }}?noverify=true" disabled></input>
        </p>
        <p>
          <a class="uk-button uk-button-default" href="{{ route('admin.entry.resend', ['id' => $entry->id]) }}">Resend
            Link</a>
        </p>
      </div>
    </div>
  </div>
@endsection
