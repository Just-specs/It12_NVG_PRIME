@props(['url'])
@php
    $logoUrl = rtrim(config('app.url'), '/') . '/img/NVG_LOGO_org.png';
    $appName = config('app.name', 'NVG Prime');
@endphp
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ $logoUrl }}" class="logo" alt="{{ $appName }} Logo">
</a>
</td>
</tr>
