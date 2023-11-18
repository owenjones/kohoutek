<x-mail::message>
# Entry Received

Hello {{ $contact_name }},

Your Kohoutek entry for {{ $group }} has been received! Please follow the link below to access the Kohoutek Portal for the first time and verify your entry.

<x-mail::button :url="$login_url" color="success">
Login to Kohoutek Portal
</x-mail::button>

This link gives access to the Kohoutek Portal and is unique to your entry, please don't share it outside your
Group/Unit.

Thanks,<br>
Team Kohoutek

<small>If you're unable to click the link above, copy and paste it into your browser:<br /><em>{{ $login_url }}</em></small>
</x-mail::message>
