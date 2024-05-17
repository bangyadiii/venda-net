<div>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Data Pelanggan</span>
    </h4>
    <div class="card">
        <div class="card-body">
            <div class="text-end mb-3">
                <a href="{{ route('customers.create') }}" wire:navigate class="btn btn-primary">Tambah Pelanggan</a>
                <button class="btn btn-secondary">Sinkron</button>
            </div>
            <livewire:customer-table />
        </div>
    </div>
</div>