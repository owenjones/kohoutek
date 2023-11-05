@if ($errors->any())
  <div class="uk-alert uk-alert-danger">
    @if (count($errors) > 1)
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @else
      {{ $errors->all()[0] }}
    @endif
  </div>
@endif
