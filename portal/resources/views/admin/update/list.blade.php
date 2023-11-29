@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container uk-container-medium">
      <div class="uk-flex uk-flex-stretch" uk-grid>
        <div class="uk-width-2-3">
          <h2>Updates</h2>
        </div>
        <div class="uk-width-1-3 uk-text-right">
          <a class="uk-button uk-button-secondary" href="{{ route('admin.update.new') }}">Send Update</a>
        </div>
        <div class="uk-width-1-1">
          @include('components.alerts')
          @foreach ($updates as $update)
            <div class="uk-margin" uk-grid>
              <div class="uk-width-5-6">
                <h3>{{ $update->title }}</h3>
              </div>
              <div class="uk-width-1-6 uk-text-right uk-text-small">
                {{ $update->date }}
              </div>
              <div class="uk-width-1-1">
                {!! $update->formatted !!}
                @if (!$loop->last)
                  <hr class="uk-divider-icon">
                @endif
              </div>
            </div>
          @endforeach
          {{ $updates->links('components.simple-paginator') }}
        </div>
      </div>
    </div>
  </div>
@endsection
