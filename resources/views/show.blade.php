@extends ('layouts.master')

@section ('title')
Reset password
@endsection


@section ('content')

<div id="app">
	<reset-password userinfo="{{ json_encode($userinfo) }}" token="{{ $pass->pass }}"></reset-password>
	<noscript>This page does not work without JavaScript</noscript>
</div>

<script src="/js/app.js"></script>

@endsection