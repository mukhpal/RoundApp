@component('mail::message')
# {{ __('Verify Email') }}

{{ __('Please click the button below to verify your email address.') }}

@component('mail::button', ['url' => $url])
{{ __('Verify Email Address') }}
@endcomponent

{{ __('If you did not create an account, no further action is required.') }}


{{ __('Regards') }},<br>
{{ config('app.name') }}



<hr style="border:1px solid #e8e5ef"> </hr>
<p style="font-size: 12px;">
If you’re having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:<br>
    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
</p>

@endcomponent
