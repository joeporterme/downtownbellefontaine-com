@props([
    // Comma string or array of Maps JS libraries, e.g. "places".
    'libraries' => null,
    // Global callback function name invoked once the SDK is ready.
    'callback' => null,
    // Google's recommended async loading; pass false only if a caller needs the
    // legacy synchronous behaviour.
    'loading' => 'async',
])
@php
    $params = ['key' => config('services.google.maps_browser_key')];

    if (! empty($libraries)) {
        $params['libraries'] = is_array($libraries) ? implode(',', $libraries) : $libraries;
    }
    if (! empty($callback)) {
        $params['callback'] = $callback;
    }
    if (! empty($loading)) {
        $params['loading'] = $loading;
    }

    $src = 'https://maps.googleapis.com/maps/api/js?' . http_build_query($params);
@endphp
<script async defer src="{{ $src }}"></script>
