<x-mail::message>
# New Kohoutek Account

Hello {{ $name }},

A new Kohouek administration account has been added for you.

Please use the link below to set your password and login, it'll be active for 24 hours.

<x-mail::button :url="$token_url" color="success">
Set Password
</x-mail::button>

Thanks,<br>
Team Kohoutek

<small>If you're unable to click the link above, copy and paste it into your browser:<br /><em>{{ $token_url }}</em></small>
</x-mail::message>
