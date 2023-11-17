@extends('admin.template')
@section('body')
  <div class="uk-section uk-section-default uk-section-xsmall">
    <div class="uk-container uk-container-small">
      @include('components.alerts')

      <div>
        <h2 class="uk-text-danger">Delete {{ $user->name }}</h2>
        <p>To delete <strong>{{ $user->name }}</strong> please type "DELETE {{ $user->email }}" below.</p>
        <form method="post" action="{{ route('admin.user.delete', ['id' => $user->id]) }}">
          @csrf
          <input type="text" class="uk-input" name="confirm" placeholder="DELETE..." />
          @include('components.form-errors')
          <input type="submit" class="uk-button uk-button-danger uk-margin" value="Delete user" />
        </form>
      </div>

    </div>
  </div>
@endsection
