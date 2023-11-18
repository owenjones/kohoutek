@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h4 class="uk-card-title">Notes</h4>
    <form method="POST" action="{{ route('admin.entry.notes', ['id' => $entry->id]) }}">
      @csrf
      <input type="hidden" name="id" value="{{ $entry->id }}" />
      <textarea class="uk-textarea" name="notes" placeholder="Notes" rows="10">{{ old('notes', $entry->notes) }}</textarea>
      @include('components.form-errors')
      <button type="submit" class="uk-button uk-button-primary uk-margin">Save</button>
    </form>
  </div>
@endsection
