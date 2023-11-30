@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-1-1">
          <h2>Settings</h2>
          @include('components.alerts')
        </div>
        <div class="uk-width-1-1 uk-width-1-2@m">
          <form method="post" action="{{ route('admin.settings') }}">
            @csrf

            <fieldset class="uk-fieldset uk-form-horizontal">
              <h3>General</h3>
              <label class="uk-form-label">Competition year</label>
              <div class="uk-form-controls">
                <input type="text" name="year" class="uk-input uk-margin"
                  value="{{ old('year', settings()->get('year')) }}" required />
              </div>

              <h3>Teams</h3>
              <label class="uk-form-label">Max number of teams</label>
              <div class="uk-form-controls">
                <input type="text" name="max_teams" class="uk-input uk-margin"
                  value="{{ old('max_teams', settings()->get('max_teams')) }}" required />
              </div>

              <label class="uk-form-label">Max teams per group</label>
              <div class="uk-form-controls">
                <input type="text" name="max_group_teams" class="uk-input uk-margin"
                  value="{{ old('max_group_teams', settings()->get('max_group_teams')) }}" required />
              </div>

              <h3>Sign up</h3>
              <label class="uk-form-label">Sign up form open</label>
              <div class="uk-form-controls uk-form-controls-text">
                <input type="checkbox" name="signup_open" class="uk-checkbox uk-margin" value="true"
                  @checked(old('signup_open', settings()->get('signup_open', false))) />
              </div>

              <label class="uk-form-label">Initial teams</label>
              <div class="uk-form-controls">
                <input type="text" name="initial_teams" class="uk-input uk-margin"
                  value="{{ old('initial_teams', settings()->get('initial_teams')) }}" required />
              </div>

              <h3>Payments</h3>
              <label class="uk-form-label">Team fee</label>
              <div class="uk-form-controls">
                <input type="text" name="payment_fee" class="uk-input uk-margin"
                  value="{{ old('payment_fee', settings()->get('payment_fee')) }}" required />
              </div>

              <label class="uk-form-label">Account name</label>
              <div class="uk-form-controls">
                <input type="text" name="payment_account_name" class="uk-input uk-margin"
                  value="{{ old('payment_account_name', settings()->get('payment_account_name')) }}" required />
              </div>

              <label class="uk-form-label">Account sort code</label>
              <div class="uk-form-controls">
                <input type="text" name="payment_sort_code" class="uk-input uk-margin"
                  value="{{ old('payment_sort_code', settings()->get('payment_sort_code')) }}" required />
              </div>

              <label class="uk-form-label">Account number</label>
              <div class="uk-form-controls">
                <input type="text" name="payment_account_number" class="uk-input uk-margin"
                  value="{{ old('payment_account_number', settings()->get('payment_account_number')) }}" required />
              </div>

              <label class="uk-form-label">Reference prefix</label>
              <div class="uk-form-controls">
                <input type="text" name="payment_prefix" class="uk-input uk-margin"
                  value="{{ old('payment_prefix', settings()->get('payment_prefix')) }}" required />
              </div>
            </fieldset>

            @include('components.form-errors')

            <button type="submit" class="uk-button uk-button-default uk-margin">Save Settings</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
