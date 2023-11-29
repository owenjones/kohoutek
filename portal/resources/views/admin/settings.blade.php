@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container uk-container-medium">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-1-1">
          <h2>Settings</h2>
          @include('components.alerts')
        </div>
        <div class="uk-width-1-2">
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

              <label class="uk-form-label">Initial teams</label>
              <div class="uk-form-controls">
                <input type="text" name="initial_teams" class="uk-input uk-margin"
                  value="{{ old('initial_teams', settings()->get('initial_teams')) }}" required />
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
