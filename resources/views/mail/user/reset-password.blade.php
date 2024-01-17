@component('mail::message')
# {{ __('Hello') }}

{{ __('You are receiving this email because we received a password reset request for your account.') }}

@component('mail::button', ['url' => $url])
{{ __('Reset Password') }}
@endcomponent

{{ __('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]) }}
{{ __('If you did not request a password reset, no further action is required.') }}

{{ __('Regards') }},<br>
{{ config('app.name') }}



<hr style="border:1px solid #e8e5ef"> </hr>
<p style="font-size: 12px;">
If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
    <a href="{{ $url }}" target="_blank">{{ $url }}</a>
</p>

@endcomponent
