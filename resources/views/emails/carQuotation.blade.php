@component('mail::message')
# {{ $details['title'] }}



@component('mail::panel')
Name: {{ $details['name'] }}
@endcomponent

@component('mail::panel')
Email: {{ $details['email'] }}
@endcomponent

@component('mail::panel')
Contact: {{ $details['contact'] }}
@endcomponent

@component('mail::panel')
Model: {{ $details['model'] }}
@endcomponent

@component('mail::panel')
Year: {{ $details['year'] }}
@endcomponent
        
Thanks,<br>
{{ config('app.name') }}
@endcomponent
