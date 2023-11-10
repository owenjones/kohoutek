@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h4 class="uk-card-title">Resend Team Portal Link</h4>
    <p>This will resend the team portal link to the entry contact.</p>
    <form method="POST" action="{{ route('admin.entry-resend', ['id' => $entry->id]) }}">
      @csrf
      @include('components.form-errors')
      <input type="hidden" name="id" value="{{ $entry->id }}" />
      <button type="submit" class="uk-button uk-button-primary">Resend link</button>
    </form>
  </div>
@endsection
