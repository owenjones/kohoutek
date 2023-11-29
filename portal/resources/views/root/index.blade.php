@extends('root.template')

@section('body')
  @include('root.section.menu')
  @include('root.section.home')

  @if ($signupsOpen)
    @if ($spacesAvailable)
      @include('root.section.signup')
    @else
      @include('root.section.signup-full')
    @endif
  @else
    @include('root.section.signup-closed')
  @endif

  @include('root.section.about')
  @include('root.section.history')
  @include('root.section.footer')
@endsection
