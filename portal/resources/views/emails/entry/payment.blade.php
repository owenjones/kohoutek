<x-mail::message>
# Payment Received

Hello {{ $contact_name }},

Your payment for Kohoutek ({{ $amount }}) has been received - thanks!

If you need it, your login link is below.

<x-mail::button :url="$login_url" color="success">
Login to Kohoutek Portal
</x-mail::button>

Thanks,<br>
Team Kohoutek

<small>If you're unable to click the link above, copy and paste it into your browser:<br /><em>{{ $login_url }}</em></small>
</x-mail::message>
