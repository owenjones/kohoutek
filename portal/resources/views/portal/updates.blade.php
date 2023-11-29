@extends('portal.template')
@section('body')
  <div class="uk-width-1-1@m">
    <h2 class="uk-text-center uk-text-left@m">Updates</h2>
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
@endsection
