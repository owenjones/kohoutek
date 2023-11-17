@extends('admin.auth.template')
@section('body')
  <div class="uk-card uk-card-default uk-position-center">
    <div class="uk-card-body">
      <h4 class="uk-card-title uk-text-center">Set Password</h4>
      @include('components.alerts')

      <form method="POST" action="{{ route('admin.login.set-password', ['token' => $token]) }}" class="uk-form-stacked">
        @csrf
        <div class="uk-margin">
          <label class="uk-form-label">Password</label>
          <div class="uk-form-controls uk-inline uk-width-1-1">
            <input required class="uk-input uk-form-large" name="password" type="password" />
          </div>
        </div>

        <div class="uk-margin">
          <label class="uk-form-label">Confirm Password</label>
          <div class="uk-form-controls uk-inline uk-width-1-1">
            <input required class="uk-input uk-form-large" name="password_confirmation" type="password" />
          </div>
        </div>

        @include('components.form-errors')

        <button type="submit" class="uk-button uk-button-default uk-button-large uk-width-1-1">Set</button>
    </div>
    </form>
  </div>
  </div>
@endsection
