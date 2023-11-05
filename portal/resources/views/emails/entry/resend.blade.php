<x-mail::message>
# Kohoutek Portal Link

Hello {{ $contact_name }}, your Kohoutek portal link for {{ $group }} is below.

<x-mail::button :url="$login_url" color="success">
Login to Kohoutek Portal
</x-mail::button>

This link gives access to the Kohoutek Portal and is unique to your entry, please don't share it outside your
Group/Unit.

Thanks,<br>
Team Kohoutek

<small>If you're unable to click the link above, copy and paste it into your browser:<br /><em>{{ $login_url }}</em></small>
</x-mail::message>
