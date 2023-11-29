@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-1-1">
          <h2>Updates</h2>
          @include('components.alerts')

          <form method="POST" action="{{ route('admin.update.new') }}">
            @csrf

            <fieldset class="uk-fieldset uk-form-stacked">
              <label class="uk-form-label">Title</label>
              <div class="uk-form-controls">
                <input type="text" name="title" class="uk-input uk-margin" placeholder="Title"
                  value="{{ old('title') }}" required />
              </div>
            </fieldset>

            <textarea class="uk-textarea" name="message" placeholder="Message" rows="10" required>{{ old('message') }}</textarea>

            @include('components.form-errors')

            <button type="submit" class="uk-button uk-button-primary uk-margin">Send Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
