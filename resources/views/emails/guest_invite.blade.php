<x-mail::message>
# You have been invited!

You have been invited to join the event. Please click the button below to create your guest account and view the details.

<x-mail::button :url="$link">
Accept Invitation
</x-mail::button>

If you did not request this invitation, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>