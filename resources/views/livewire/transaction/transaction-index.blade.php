<div>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Tagihan & Pembayaran</span>
    </h4>
    <div class="card">
        <div class="card-body">
            <div class="text-end mb-3">
                <a href="{{ route('transactions.create') }}" wire:navigate class="btn btn-primary">Tambah Pembayaran</a>
            </div>
            <livewire:transaction.transaction-table />
        </div>
    </div>
</div>