<?php
$alerts = session('alert');
?>

@if ($alerts)
    <div class="uk-width-1-1">
        @foreach ($alerts as $type => $message)
            <div class="uk-alert uk-alert-{{ type }}">
                {{ message | safe }}
            </div>
        @endforeach
    </div>
@endif
