@extends('portal.template')
@section('body')
  <div class="uk-width-1-1@m">
    <h3 class="uk-text-center uk-text-left@m">Rename Team {{ $team->code }}</h3>
    <p>Enter your new team name below: this is the name which will be used on your teams' certificates and on
      the post-event scoreboard.</p>
    @include('components.alerts')
    <form method="post" action="{{ route('portal.team.rename', ['id' => $team->id]) }}">
      @csrf
      <label class="uk-form-label">Team name</label>
      <div class="uk-form-controls">
        <input type="text" name="name" class="uk-input" placeholder="{{ $team->name }}" value="{{ old('name') }}"
          required />
      </div>
      @include('components.form-errors')
      <button type="submit" class="uk-button uk-button-primary uk-margin">Change Name</button>
    </form>
  </div>
@endsection
