<div>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Data Pelanggan</span>
    </h4>
    <div class="card">
        <div class="card-body">
            <div class="text-end mb-3">
                <a href="{{ route('customers.create') }}" wire:navigate class="btn btn-primary">Tambah Pelanggan</a>
                <div>
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <output class="spinner-border me-2 spinner-border-sm" wire:loading>
                            <span class="visually-hidden">Loading...</span>
                        </output>
                        Sinkron
                    </button>
                    <ul class="dropdown-menu">
                        @foreach ($routers as $router)
                        <li>
                            <button class="dropdown-item" wire:click='syncSecret({{ $router->id }})'
                                wire:confirm='Are you sure to sync secret from {{ $router->host }}?'>
                                {{ $router->host }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <livewire:customer-table />
        </div>
    </div>
</div>