@extends('portal.auth.template')
@section('body')
  <div class="uk-position-center">
    <div class="uk-card uk-card-default uk-width-1-2@m uk-margin-auto">
      <div class="uk-card-body">
        <h4 class="uk-card-title">Kohoutek Team Portal</h4>
        <p>
          To access the team portal you need to use the secure login link emailed to you when you signed up.
        </p>
        <p>
          If you're having trouble logging in with your portal link, please try <a
            href="https://support.google.com/accounts/answer/32050" target="_blank" rel="nofollow noreferrer">clearing your
            cookies</a> before following the link again.
        </p>
        <p>
          We can <a href="{{ route('portal.resend') }}">resend your team portal link</a> if you can't find it
          (but please look first, we have to pay if we send too many emails!).
        </p>
        <p>
          If you haven't received a sign up confirmation email, or can't remember the email address you signed up
          with, please contact us at <a href="mailto:contact@kohoutek.co.uk">contact@kohoutek.co.uk</a>.
        </p>
      </div>
    </div>
  </div>
@endsection
