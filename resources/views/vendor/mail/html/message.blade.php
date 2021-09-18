@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
&copy; {{ date('Y') }}, K-Net Association, CVR: 30772652, Anker Engelunds Vej 101A, 2800 Kgs. Lyngby, Denmark.
@endcomponent
@endslot
@endcomponent
