@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h2 class="uk-card-title uk-text-danger">Cancel Entry</h2>
    <p>This will irreversibly remove the entry, and any orders and submitted scores associated with it.</p>
    <p>If you're certain this is what you want to do, enter the entry ID <strong>{{ $entry->id }}</strong> below.</p>
    <form method="POST" action="{{ route('admin.entry-cancel', ['id' => $entry->id]) }}">
      @csrf
      <input type="hidden" name="id" value="{{ $entry->id }}" />
      <input type="number" name="verify_id" class="uk-input uk-form-large" placeholder="Entry ID" value="" required />
      <div class="uk-margin">
        <label><input type="checkbox" name="silent" value="true" class="uk-checkbox"> - cancel silently</label>
      </div>
      @include('components.form-errors')
      <button type="submit" class="uk-button uk-button-danger">Cancel Entry</button>
    </form>
  </div>
@endsection
