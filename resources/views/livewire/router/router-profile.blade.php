<div>
    @if (!empty($router->profiles))
    {{  $router->profiles->where('id', $router->isolir_profile_id)->first()?->name ?? $router->isolir_profile_id}}
    @elseif ($router->isolir_profile_id)
    {{ $router->isolir_profile_id }}
    @else
    -
    @endif
</div>