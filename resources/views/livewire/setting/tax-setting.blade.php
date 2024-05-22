<div>
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Setting /</span> Notifikasi</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Setting Notifikasi</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent='store'>
                        @csrf
                        <div class="mb-3 col-4">
                            <label class="form-label" for="format">PPN</label>
                            <div class="input-group">
                                <input id="format" type="number" class="form-control @error('ppn')
                                is-invalid
                            @enderror" wire:model='ppn' />
                                <span class="input-group-text">%</span>
                            </div>

                            @error('ppn')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary" wire:loading.attr='disabled'>
                            <div class="spinner-border" role="status" wire:loading>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove>
                                Simpan
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>