<x-mail::message>
# Payment Received

Hello {{ $contact_name }},

Your payment for Kohoutek ({{ $amount }}) has been received - thanks!

Thanks,<br>
Team Kohoutek

If you need it, your login link is below.

<x-mail::button :url="$login_url">
Login to Kohoutek Portal
</x-mail::button>

<small>If you're unable to click the link above, copy and paste it into your browser:<br /><em>{{ $login_url }}</em></small>
</x-mail::message>
