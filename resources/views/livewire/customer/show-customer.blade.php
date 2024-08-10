<div>
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Pelanggan/</span> Edit</h4>

    <form wire:submit='store' method="POST">
        @csrf
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class=" col-12 col-md-8">
                            <label class="form-label" for="name">Nama</label>
                            <input type="text" class="form-control @error('form.customer_name')
                                is-invalid
                            @enderror" wire:model="form.customer_name" id="name" placeholder="Masukan Nama Pelanggan"
                                disabled />
                            @error('form.customer_name')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label" for="phone_number">No Telp/WA</label>
                            <input type="text" class="form-control @error('form.phone_number')
                                is-invalid
                            @enderror" id="phone_number" wire:model="form.phone_number" placeholder="089123456790"
                                disabled />
                            @error('form.phone_number')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="address">Alamat</label>
                        <textarea type="text" class="form-control @error('form.address')
                                is-invalid
                            @enderror" id="address" wire:model="form.address"
                            placeholder="RT 02 RW 11, Depannya rumah Pak Dedik" disabled></textarea>
                        @error('form.address')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="mb-3 row" x-data="{tarif: 0, diskon: $wire.entangle('form.discount'), total: 0}">
                        <div class="col-md-12 col-xl-3 mb-2">
                            <label class="form-label" for="plan">Pilih Paket Pelanggan</label>
                            <select type="text" id="plan" class="form-select @error('form.plan_id')
                            is-invalid
                        @enderror" wire:model='form.plan_id'
                                x-on:change="tarif = $event.target.selectedOptions[0].getAttribute('data-tarif')"
                                disabled>
                                <option>Pilih Paket</option>
                                @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" data-tarif="{{ $plan->price }}">
                                    {{ $plan->name . ' | ' . $plan->router->host }}
                                </option>
                                @endforeach
                            </select>
                            @error('form.plan_id')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6 col-xl-3 mb-2">
                            <label class="form-label" for="tarif_bulanan">Tarif per Bulan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" x-bind:value="tarif" disabled />
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-2">
                            <label class="form-label" for="discount">Diskon</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" x-model='diskon' wire:model='form.discount'
                                    disabled />
                            </div>
                        </div>
                        <div class="col-md-12 col-xl-3 mb-2">
                            <label class="form-label" for="total_price">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" disabled x-bind:value="tarif - diskon"
                                    disabled />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-body" x-data="{
                username: $wire.entangle('form.secret_username'),
                password: $wire.entangle('form.secret_password'),
             
            }">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="secret_username">PPP Username</label>
                        <input type="text" class="form-control @error('form.secret_username')
                                is-invalid
                            @enderror" id="secret_username" wire:model="form.secret_username" x-model='username'
                            disabled />

                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="secret_password">PPP Password</label>
                        <input type="text" class="form-control @error('form.secret_password')
                                is-invalid
                            @enderror" id="secret_password" wire:model="form.secret_password" x-model='password'
                            disabled />

                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="service">Service</label>
                        <select type="text" class="form-select @error('form.ppp_service')
                                is-invalid
                            @enderror" id="service" wire:model="form.ppp_service" disabled>
                            <option>Pilih Service</option>
                            @foreach ($form->serviceList as $service)
                            <option value="{{ $service['value'] }}">{{ $service['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="local_address">Local Address</label>
                        <input type="text" class="form-control" wire:model='form.local_address' disabled />
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="remote_address">Remote Address</label>
                        <input type="text" class="form-control" wire:model='form.remote_address' disabled />
                        <a href="http://{{ $form->remote_address }}" target="_blank">{{ $form->remote_address }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body" x-data="{
                isSameDate: $wire.entangle('form.isSameDate'),
                installmentStatus: $wire.entangle('form.installment_status'),
                serviceStatus: $wire.entangle('form.service_status'),
                serviceStatusOptions: {
                    'installed': $wire.entangle('form.serviceStatusOptions'),
                    'not_installed': ['inactive']
                },
                activeDate: $wire.entangle('form.active_date'),
                notIsolir: !$wire.entangle('form.auto_isolir'),
                isolirDate: $wire.entangle('form.isolir_date'),
            }">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="installment_status">Status Pemasangan</label>
                        <select type="text" class="form-select @error('form.installment_status')
                                is-invalid
                            @enderror" wire:model="form.installment_status" id="installment_status"
                            x-on:change="updateServiceStatusOptions" disabled>
                            <option value="installed">TERPASANG</option>
                            <option value="not_installed">BELUM TERPASANG</option>
                        </select>
                        @error('form.installment_status')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="service_status">Status Layanan</label>
                        <select type="text" class="form-select @error('form.service_status') is-invalid @enderror"
                            id="service_status" wire:model="form.service_status" x-model="serviceStatus" disabled>
                            <template x-for="option in serviceStatusOptions[installmentStatus]" :key="option">
                                <option x-text="option" :value="option"></option>
                            </template>
                        </select>
                        @error('form.service_status')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="active_date">Tanggal Aktif Pemakaian</label>
                        <input type="date" class="form-control @error('form.active_date')
                                is-invalid
                            @enderror" id="active_date" wire:model="form.active_date" x-model="activeDate"
                            x-on:change="toggleIsolirDate" disabled />
                        @error('form.active_date')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="isolir_date" x-show='!notIsolir' x-transition>Batas Pembayaran /
                            ISOLIR</label>
                        <select type="text" id="isolir_date" class="form-select @error('form.isolir_date')
                            is-invalid
                        @enderror" wire:model='form.isolir_date' x-model="isolirDate" disabled>
                            <option value>Pilih Tanggal</option>
                            @for ($i = 1; $i <= 28; $i++) <option value="{{ $i }}">Tanggal {{ $i }}</option>
                                @endfor
                                <option value="last_day">Akhir Bulan</option>

                        </select>
                        @error('form.isolir_date')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
                        wire:model='form.auto_isolir' x-model='notIsolir' disabled>
                    <label class="form-check-label" for="flexSwitchCheckDefault">Jangan isolir otomatis pelanggan
                        ini?</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="toggle-date" x-on:click="toggleIsolirDate"
                        disabled x-model="isSameDate">
                    <label class="form-check-label" for="toggle-date" disabled>
                        Sesuai Tanggal Aktif Pemakaian
                    </label>
                </div>

                <a href="{{ route('customers.edit', $customer->id) }}" wire:navigate wire:loading.attr='disabled'
                    class="btn btn-primary">Edit</a>
                <a href="{{ route('customers.index') }}" wire:navigate wire:loading.attr='disabled'
                    class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>


    @section('page-script')
    <script>
        function updateServiceStatusOptions() {
        if (this.installmentStatus === 'not_installed') {
           this.serviceStatus = 'inactive';
           this.activeDate = null;
           this.isolirDate = null;
        }
    }
    
    function toggleIsolirDate() {
        if (this.isSameDate) {
            let active = new Date(this.activeDate);
            if(isNaN(active.getTime())) return;
            if (active.getDate() <= 28){
                this.isolirDate = active.getDate();
            } else{
                this.isolirDate = 'last_day';
            }
        }
    }
    </script>
    @endsection
</div>