<div>
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Paket/</span> Edit</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Paket</h5>
                </div>
                <div class="card-body">
                    <form wire:submit='store'>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="name">Nama Paket</label>
                            <input type="text" class="form-control @error('form.name')
                                is-invalid
                            @enderror" id="name" placeholder="Nama Paket" wire:model="form.name" />
                            @error('form.name')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="speed">Kecepatan</label>
                            <input type="text" class="form-control @error('form.speed_limit')
                                is-invalid
                            @enderror" wire:model="form.speed_limit" id="speed" placeholder="1" />
                            @error('form.speed_limit')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tarif">Tarif per Bulan</label>
                            <input type="text" id="tarif" class="form-control @error('form.price')
                                is-invalid 
                            @enderror" placeholder="Masukan Tarif per bulan" wire:model='form.price' />
                            @error('form.price')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="tarif">Router</label>
                            <select type="text" id="tarif" class="form-select @error('form.router_id')
                                is-invalid
                            @enderror" wire:model='form.router_id' disabled>
                                <option>Pilih Router</option>
                                @foreach ($routers as $router)
                                <option value="{{ $router->id }}">{{ $router->host }}</option>
                                @endforeach
                            </select>
                            @error('form.router_id')
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
                        <a class="btn btn-secondary" wire:navigate href="{{ route('plans.index') }}"
                            wire:loading.attr='disabled'>Batal</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>