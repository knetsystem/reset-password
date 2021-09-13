@component('mail::message', ['preheader' => '
We received a request to reset your password with this email address. ('.$user['email'].')'])

# Hi,


We received a request to reset the password to access K-Net bruger hos {{config('app.name')}} with your email address ({{ $user['email'] }}) from a {{ $platform }} device using {{ $browser }}, but we were unable to find an account associated with this address.

If you are a K-Net user and were expecting this email, consider trying to request a password reset using the email address associated with your account.

@component('mail::button', ['url' => config('app.url')])
Try a different email
@endcomponent

If you are not a K-Net user or did not request a password reset, please ignore this email or [contact support]({{ env('SUPPORT_URL') }}) if you have questions.

Thanks,<br>
K-Net Association

@component('mail::subcopy')
If you’re having trouble with the button above, copy and paste the URL below into your web browser.

{{ config('app.url') }}
@endcomponent

@endcomponent
