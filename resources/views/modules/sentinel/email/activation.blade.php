@component('mail::message')
Hooray <strong>{{$user->username}}</strong> Welcome to {{env("APP_NAME")}}<br>Just a few more steps to activate your account.

<a class="btn btn-primary" href="{{ route('activation.activate', $activationParameters) }}"> click here</a>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
