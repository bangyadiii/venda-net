<div>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Setting Mikrotik</span>
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
                                <th>Aksi Isolir</th>
                                <th>Profil Isolir</th>
                                <th>Koneksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($routers as $router)
                            <tr wire:key='{{ $router->id }}'>
                                <td><span class="fw-medium">{{ $router->host }}</span></td>
                                <td>{{ $router->isolir_action == 'change_profile' ? 'Ganti Profile' : 'Disable Secret'}}
                                </td>
                                <td>
                                    <livewire:router.router-profile lazy :router="$router">
                                </td>

                                <td>
                                    <livewire:router.router-connection lazy :router="$router">
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
                                                wire:confirm='Are you sure to delete this?' wire:loading.attr='disabled'><i
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