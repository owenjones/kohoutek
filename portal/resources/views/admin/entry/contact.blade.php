@extends('admin.entry.frame')
@section('module')
  <div class="uk-card uk-card-default uk-card-body uk-width-1-1">
    <h4 class="uk-card-title">Contact Entry</h4>
    <form method="POST" action="{{ route('admin.entry-contact', ['id' => $entry->id]) }}">
      @csrf
      <input type="hidden" name="id" value="{{ $entry->id }}" />

      <fieldset class="uk-fieldset uk-form-horizontal">
        <label class="uk-form-label">Recipient</label>
        <div class="uk-form-controls">
          <input type="text" class="uk-input uk-margin"
            value="{{ $entry->contact_name }} <{{ $entry->contact_email }}>" disabled />
        </div>

        <label class="uk-form-label">Subject</label>
        <div class="uk-form-controls">
          <input type="text" name="subject" class="uk-input uk-margin" placeholder="Subject"
            value="{{ old('subject') }}" required />
        </div>
      </fieldset>

      <textarea class="uk-textarea" name="message" placeholder="Message" rows="10" required>{{ old('message') }}</textarea>

      @include('components.form-errors')

      <button type="submit" class="uk-button uk-button-primary uk-margin">Send Email</button>
    </form>
  </div>
@endsection
