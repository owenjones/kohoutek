@extends('auth.template')
@section('body')
  <div class="uk-card uk-card-default uk-position-center">
    <div class="uk-card-body">
      <h4 class="uk-card-title uk-text-center">Team Kohoutek Login</h4>
      <form method="POST" action="{{ route('admin.login') }}" class="uk-form-stacked">
        @csrf
        <div class="uk-margin">
          <div class="uk-form-controls uk-inline">
            <span class="uk-form-icon" uk-icon="icon: user"></span>
            <input required class="uk-input uk-form-large" id="username" name="username" type="text"
              value="{{ old('username') }}" />
          </div>
        </div>

        <div class="uk-margin">
          <div class="uk-form-controls uk-inline">
            <span class="uk-form-icon" uk-icon="icon: lock"></span>
            <input required class="uk-input uk-form-large" id="password" name="password" type="password" />
          </div>
        </div>

        @include('components.form-errors')

        <button type="submit" class="uk-button uk-button-default uk-button-large uk-width-1-1">Login</button>
    </div>
    </form>
  </div>
  </div>
@endsection
