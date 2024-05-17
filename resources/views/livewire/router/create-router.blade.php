<div>
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Router/</span> Create</h4>

    <!-- Basic Layout -->
    <form wire:submit='store' x-data="{
        autoIsolir: $wire.entangle('form.auto_isolir'),
        isolirAction: $wire.entangle('form.isolir_action'),
    }">
        @csrf
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tambah Router</h5>
                    </div>
                    <div class="card-body">
                        <div class="has-validation mb-3">
                            <label class="form-label" for="host">Host</label>
                            <input type="text" class="form-control @error('form.host')
                                is-invalid
                            @enderror" wire:model="form.host" id="host" placeholder="Remote URL" />
                            @error('form.host')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" class="form-control @error('form.username')
                                is-invalid
                            @enderror" id=" username" wire:model="form.username" placeholder="Username" />
                            @error('form.username')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" class="form-control" placeholder="Input your password"
                                wire:model='form.password' />
                            @error('form.password')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button class="btn btn-primary" wire:click='testConnection' wire:loading.attr='disabled'
                            type="button">
                            <div class="spinner-border" role="status" wire:loading wire:target='testConnection'>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove wire:target='testConnection'>
                                Test Connection
                            </span>
                        </button>

                        <a wire:navigate href="{{ route('routers.index') }}" wire:loading.attr='disabled'
                            class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </div>
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Setting Isolir</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="auto_isolir">Auto Isolir ?</label>
                            <select class="form-select @error('form.auto_isolir')
                                is-invalid
                            @enderror" id="auto_isolir" wire:model="form.auto_isolir" x-model="autoIsolir" :change="() => {
                                if (autoIsolir == false) {
                                    isolirAction = null;
                                }
                            }">
                                <option value="1">Ya</option>
                                <option value="0">Tidak</option>
                                @error('form.auto_isolir')
                                <div class="error">
                                    {{ $message }}
                                </div>
                                @enderror
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="isolir_action">Action Isolir</label>
                            <select class="form-select @error('form.isolir_action')
                                is-invalid
                            @enderror" id="isolir_action" wire:model="form.isolir_action" x-model="isolirAction"
                                :disabled="autoIsolir == false">
                                <option value>Pilih Action</option>
                                <option value="change_profile">UBAH SECRET PROFILE</option>
                                <option value="disable_secret">DISABLE SECRET</option>
                            </select>
                            @error('form.isolir_action')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="isolir_profile_id">Pilih Profile Isolir</label>
                            <select class="form-select @error('form.isolir_profile_id')
                               is-invalid
                            @enderror" id="isolir_profile_id" wire:model="form.isolir_profile_id"
                                :disabled="isolirAction != 'change_profile'">
                                <option value>Pilih Profile</option>
                                @foreach ($form->profiles as $profile)
                                <option value="{{ $profile['.id'] }}">{{ $profile['name'] }}</option>
                                @endforeach
                            </select>
                            @error('form.isolir_profile_id')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @if ($form->is_connected)
                        <button wire:loading.attr='disabled' type="submit" class="btn btn-primary">
                            <div class="spinner-border" role="status" wire:loading>
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span wire:loading.remove>
                                Simpan
                            </span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>