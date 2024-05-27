<div>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Router Settings</span>
    </h4>

    <div class="row">
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="text-end">
                    <a href="{{ route('routers.create') }}" wire:navigate class="btn btn-primary">Tambah Router</a>
                </div>
                <div class="table-responsive text-nowrap">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Host</th>
                                <th>Auto Isolir</th>
                                <th>Action Isolir</th>
                                <th>Profile Isolir</th>
                                <th>Koneksi</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($routers as $router)
                            <tr wire:key='{{ $router->id }}'>
                                <td><span class="fw-medium">{{ $router->host }}</span></td>
                                <td>
                                    <span
                                        class="badge bg-label-{{ $router->auto_isolir == 1 ? 'success' : 'danger' }} me-1">
                                        {{ $router->auto_isolir == 1 ? 'Yes' : 'No'}}
                                    </span>
                                </td>
                                @if ($router->auto_isolir)
                                <td>{{ $router->isolir_action == 'change_profile' ? 'Ganti Profile' : 'Disable Secret'}}
                                </td>
                                @else
                                <td>-
                                    @endif
                                <td>{{ !empty($router->profiles) ? $router->profiles->where('id', $router->isolir_profile_id)->first()?->name : '-'}}
                                </td>

                                <td><span
                                        class="badge bg-label-{{ $router->isConnected ? 'success' : 'danger' }} me-1">{{ $router->isConnected ? 'Connected' : 'Disconnected' }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i
                                                class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('routers.edit', $router->id) }}"
                                                wire:navigate><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <button class="dropdown-item" wire:click='delete({{ $router->id }})'
                                                wire:confirm='Are you sure to delete this?'><i
                                                    class="bx bx-trash me-1"></i>
                                                Delete</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>