<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'K-Net')
<img src="https://reset.k-net.dk/img/logo.png" class="logo" alt="K-Net Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
