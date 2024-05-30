@component('mail::message')
@include('components.application-logo')
Hello, {{$body['name']}}!

{!! $body['body'] !!}

@component('mail::button', ['url' => $body['action_link']])
Verify Email
@endcomponent

{!! $body['signature'] !!}}
@endcomponent
