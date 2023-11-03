@extends('portal.template')
@section('body')
    <div class="uk-position-center">
        <div class="uk-card uk-card-default uk-width-1-2@m uk-margin-auto">
            <div class="uk-card-body">
                <h4 class="uk-card-title">Resend Portal Link</h4>
                <p>
                    We can resend your portal link if you're unable to find it, please enter the email address you
                    originally used when you signed up for Kohoutek below. If you can't remember the email address you used
                    please get in touch with us at <a href="mailto:contact@kohoutek.co.uk">contact@kohoutek.co.uk</a>.
                </p>
                @include('portal.components.alerts')
                @if (!$submitted)
                    <form method="POST" action="{{ route('portal.resend') }}" class="uk-form-stacked">
                        @csrf
                        <div class="uk-margin">
                            <div class="uk-form-controls uk-inline">
                                <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                <input required class="uk-input uk-form-large uk-form-width-large" id="email"
                                    name="email" type="email" value="{{ old('email') }}" />
                            </div>
                            @include('portal.components.form-errors')
                        </div>

                        <div class="uk-margin">
                            <button type="submit" class="uk-button uk-button-primary uk-button-large">
                                Resend link
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
