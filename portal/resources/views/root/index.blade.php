@extends('root.template')
@push('head-js')
  {!! RecaptchaV3::initJs() !!}
@endpush
@section('body')
  @include('root.section.menu')
  @include('root.section.home')
  @include('root.section.about')

  @if ($signupsOpen)
    @if ($spacesAvailable)
      @include('root.section.signup')
    @else
      @include('root.section.signup-full')
    @endif
  @else
    @include('root.section.signup-closed')
  @endif

  @include('root.section.history')
  @include('root.section.footer')
@endsection
