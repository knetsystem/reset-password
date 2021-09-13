@extends ('layouts.master')

@section ('title')
Reset password
@endsection

@section ('content')

<h1>Reset password</h1>

<div id="app">
	<reset-request sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></reset-request>
	<noscript>This page does not work without JavaScript</noscript>
</div>

<script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
<script src="/js/app.js"></script>

@endsection