@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container">
      @include('components.alerts')

      <div uk-grid>
        <div class="uk-width-2-3@m">
          <h2>Users</h2>
          <table class="uk-table uk-table-responsive uk-table-striped uk-table-middle">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>
                    <div class="uk-grid uk-grid-small uk-text-right">
                      <div>
                        <form method="post" action="{{ route('admin.user.password-reset', ['id' => $user->id]) }}">
                          @csrf
                          <input type="submit" class="uk-button uk-button-default uk-button-small" value="Reset Password" />
                        </form>
                      </div>
                      <div>
                        <a href="{{ route('admin.user.delete', ['id' => $user->id]) }}"
                          class="uk-button uk-button-default uk-button-small">Delete</a>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="uk-width-1-3@m">
          <h3>Add new user</h3>
          <form method="post" action="{{ route('admin.user.add') }}">
            @csrf
            <fieldset class="uk-fieldset uk-form-vertical">
              <label class="uk-form-label">Name</label>
              <div class="uk-form-controls">
                <input type="text" class="uk-input uk-margin" name="name" placeholder="Name"
                  value="{{ old('name') }}" required />
              </div>

              <label class="uk-form-label">Email Address</label>
              <div class="uk-form-controls">
                <input type="email" name="email" class="uk-input uk-margin" placeholder="Email"
                  value="{{ old('email') }}" required />
              </div>
            </fieldset>
            @include('components.form-errors')
            <input type="submit" class="uk-button uk-button-default" value="Add User" />
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
