@component('mail::message', [
	'preheader' => 'Use this link to reset your password. The link is only valid for 24 hours.',
	'oneclickaction' => [
		'body' => 'Reset password',
		'url' => $pass,
		'description' => 'Use the button to reset your password.',
	],
])

# Hi,

You recently requested to reset your password for your K-Net account. Use the button below to reset it. **This password reset is only valid for the next 24 hours.**

@component('mail::button', ['url' => $pass])
Reset your password
@endcomponent

For security, this request was received from a {{ $platform }} device using {{ $browser }}.  If you did not request a password reset, please ignore this email or [contact support]({{ env('SUPPORT_URL') }}) if you have questions.

Thanks,<br>
K-Net Association

@component('mail::subcopy')
If you’re having trouble with the button above, copy and paste the URL below into your web browser.

{{$pass}}
@endcomponent

@endcomponent