@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h4 class="uk-card-title">Chase Entry</h4>
    <p>This will send a reminder to the entry contact to open the team portal link.</p>
    <form method="POST" action="{{ route('admin.entry.chase', ['id' => $entry->id]) }}">
      @csrf
      @include('components.form-errors')
      <input type="hidden" name="id" value="{{ $entry->id }}" />
      <button type="submit" class="uk-button uk-button-primary">Chase Entry</button>
    </form>
  </div>
@endsection
