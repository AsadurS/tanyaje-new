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
Ic: {{ $details['car_ic'] }}
@endcomponent

@component('mail::panel')
Car Plate: {{ $details['car_plate'] }}
@endcomponent
        
Thanks,<br>
{{ config('app.name') }}
@endcomponent
