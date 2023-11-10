@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h2 class="uk-card-title uk-text-danger">Cancel Entry</h2>
    <p>This will irreversibly remove the entry and any teams and scores associated with it.</p>
    <p>If you're absolutely certain this is what you want to do, enter <strong>Cancel Entry {{ $entry->id }}</strong>
      below.
    </p>
    <form method="POST" action="{{ route('admin.entry-cancel', ['id' => $entry->id]) }}">
      @csrf
      <input type="hidden" name="id" value="{{ $entry->id }}" />
      <input type="text" name="verify" class="uk-input uk-form-large" placeholder="Cancel..." value="" required />
      <div class="uk-margin">
        <label><input type="checkbox" name="silent" value="true" class="uk-checkbox"> - cancel silently (don't send a
          cancellation email to the entry)</label>
      </div>
      @include('components.form-errors')
      <button type="submit" class="uk-button uk-button-danger">Cancel Entry</button>
    </form>
  </div>
@endsection
