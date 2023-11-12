@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container uk-container-xlarge">
      @include('components.alerts')

      <div uk-grid>
        <div class="uk-width-2-3@m">
          <h2>Users</h2>
          <table class="uk-table uk-table-responsive uk-table-striped uk-table-middle">
            <thead>
              <tr>
                <th>ID</th>
                <th class="uk-table-expand">Name</th>
                <th>Email</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td class="uk-text-small uk-table-link">
                    <a href="{{ route('admin.user.view', ['id' => $user->id]) }}">{{ $user->id }}</a>
                  </td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td class="uk-text-right">
                    <a class="uk-button uk-button-default uk-button-primary">Modify</a>
                    <a class="uk-button uk-button-default uk-button-danger">Delete</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="uk-width-1-3@m">
          <h3>Add new user</h3>
        </div>
      </div>
    </div>
  </div>
@endsection
