<x-mail::message>
# Welcome, {{ $user->firstname }}!

You have been invited to attend **{{ $event->title }}**. 

Since you are new to EventNexus, we have automatically created an account for you. Please use the credentials below to log in and respond to your invitation.

<x-mail::panel>
**Login URL:** [{{ route('login') }}]({{ route('login') }}) <br>
**Email:** {{ $user->email }} <br>
**Password:** {{ $password }}
</x-mail::panel>

<x-mail::button :url="$loginUrl">
Login to Dashboard
</x-mail::button>

We recommend changing your password after your first login.

Thanks,<br>
EventNexus
</x-mail::message>