<x-mail::message>
    # Kohoutek Entry Received

    Your Kohoutek entry has been received!

    <x-mail::panel>
        Follow the link below to access the Kohoutek Portal for the first time and verify your entry.
    </x-mail::panel>

    <x-mail::button :url="$login_url" color="success">
        Login to Kohoutek Portal
    </x-mail::button>

    This link gives access to the Kohoutek Portal and is unique to your entry, please don't share it outside your
    Group/Unit.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
