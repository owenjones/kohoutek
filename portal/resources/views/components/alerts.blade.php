<?php
$alerts = session('alert');
session()->forget('alert');
?>

@if ($alerts)
  <div class="uk-width-1-1">
    @foreach ($alerts as $type => $message)
      <div class="uk-alert uk-alert-{{ $type }}">{!! $message !!}</div>
    @endforeach
  </div>
@endif
