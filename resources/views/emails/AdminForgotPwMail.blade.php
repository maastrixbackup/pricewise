@component('mail::message')
# Hello!

{{$body['body']}}

@component('mail::button', ['url' => $body['action_link']])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
