<div>
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Paket /</span> Create</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Paket</h5>
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

                        <div x-data="{
                                isSameSpeed: $wire.entangle('form.isSameSpeed'),
                            }" class="mb-3">
                            <div class="row mb-1">
                                <div class="download-speed col-6">
                                    <label class="form-label" for="download-speed">Kecepatan Download</label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control @error('form.download_speed') is-invalid @enderror"
                                            wire:model="form.download_speed" id="download-speed" placeholder="1" />
                                        <span class="input-group-text">Mbps</span>
                                    </div>
                                    @error('form.download_speed')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="upload-speed col-6">
                                    <label class="form-label" for="upload-speed">Kecepatan Upload</label>
                                    <div class="input-group">
                                        <input x-bind:disabled="isSameSpeed" type="text"
                                            class="form-control @error('form.upload_speed') is-invalid @enderror"
                                            wire:model="form.upload_speed"
                                            x-bind:value="isSameSpeed ? $wire.form.download_speed : $wire.form.upload_speed"
                                            id="upload-speed" placeholder="1" />
                                        <span class="input-group-text">Mbps</span>
                                    </div>
                                    @error('form.upload_speed')
                                    <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="form-check">
                                <input x-on:click="isSameSpeed = !isSameSpeed" class="form-check-input" type="checkbox"
                                    id="toggle-speed">
                                <label class="form-check-label" for="toggle-speed">
                                    Sama dengan download
                                </label>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="tarif">Tarif per Bulan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="tarif" class="form-control @error('form.price')
                                is-invalid
                            @enderror" placeholder="Masukan Tarif per bulan" wire:model='form.price' />
                            </div>

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
                            @enderror" wire:model='form.router_id'>
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