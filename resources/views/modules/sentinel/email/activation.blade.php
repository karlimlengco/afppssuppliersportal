@component('mail::message')
Hooray <strong>{{$user->username}}</strong> Welcome to Rent IT<br>Just a few more steps to activate your account.

@component('mail::button', ['url' => "{{ route('activation.activate', $activationParameters) }}"])
Click Here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
