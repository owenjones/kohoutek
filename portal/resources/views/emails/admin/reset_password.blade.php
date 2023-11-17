<x-mail::message>
# Reset Password

Hello {{ $name }},

Your password reset link is below, it'll be active for 24 hours.

<x-mail::button :url="$token_url" color="success">
Reset Password
</x-mail::button>

Thanks,<br>
Team Kohoutek

<small>If you're unable to click the link above, copy and paste it into your browser:<br /><em>{{ $token_url }}</em></small>
</x-mail::message>
