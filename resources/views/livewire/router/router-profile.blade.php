<div>
    @if (!empty($router->profiles))
    {{  $router->profiles->where('id', $router->isolir_profile_id)->first()?->name}}
    @elseif ($router->isolir_profile_id)
    {{ $router->isolir_profile_id }}
    @else
    -
    @endif
</div>