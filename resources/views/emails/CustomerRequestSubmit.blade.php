@component('mail::message')
# Dear {{$content['name']}},

{{$content['body']}}

@component('mail::button', ['url' => $content['action_link']])
View Your Request
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
