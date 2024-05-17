<div>
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Pelanggan/</span> Tambah</h4>

    <form wire:submit='store' method="POST" x-data="{
        planId: $wire.entangle('form.plan_id'),
    }">
        @csrf
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Pelanggan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class=" col-12 col-md-8">
                            <label class="form-label" for="name">Nama</label>
                            <input type="text" class="form-control @error('form.customer_name')
                                is-invalid
                            @enderror" wire:model="form.customer_name" id="name"
                                placeholder="Masukan Nama Pelanggan" />
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
                            @enderror" id="phone_number" wire:model="form.phone_number" placeholder="089123456790" />
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
                            placeholder="RT 02 RW 11, Depannya rumah Pak Dedik"></textarea>
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
                        @enderror" wire:model='form.plan_id' x-model="planId"
                                x-on:change="tarif = $event.target.selectedOptions[0].getAttribute('data-tarif')">
                                <option value>Pilih Paket</option>
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
                                <input type="number" class="form-control" x-model='diskon' wire:model='form.discount' />
                            </div>
                            <small class="text-body-secondary">*Diskon hanya untuk bulan pertama</small>
                        </div>
                        <div class="col-md-12 col-xl-3 mb-2">
                            <label class="form-label" for="total_price">Total</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" disabled x-bind:value="tarif - diskon" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="card mb-4" x-show="planId" x-transition>
            <div class="card-body" x-data="{
                secretType: $wire.entangle('form.secret_type'),
                username: $wire.entangle('form.secret_username'),
                password: $wire.entangle('form.secret_password'),
             
            }">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="secret_type">PPP Secret</label>
                        <select type="text" id="secret_type" class="form-select @error('form.secret_type')
                        is-invalid
                        @enderror" wire:model='form.secret_type' x-model="secretType" x-on:change="
                        if ($event.target.value === 'existing_secret') {
                            username = '';
                            password = '';
                        }">
                            @foreach ($form->secretTypeSelect as $secretType)
                            <option value="{{ $secretType['value'] }}">{{ $secretType['label'] }}</option>
                            @endforeach
                        </select>
                        @error('form.secret_type')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="secret_username">PPP Username</label>
                        <input type="text" class="form-control @error('form.secret_username')
                                is-invalid
                            @enderror" id="secret_username" wire:model="form.secret_username" x-model='username' />
                        @error('form.secret_username')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror

                        <button x-show="secretType == 'existing_secret'" x-transition
                            class="btn btn-secondary btn-sm mt-2" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#searchUsername" aria-controls="searchUsername">
                            Cari Username
                        </button>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="secret_password">PPP Password</label>
                        <input type="text" class="form-control @error('form.secret_password')
                                is-invalid
                            @enderror" id="secret_password" wire:model="form.secret_password" x-model='password'
                            :disabled="secretType === 'existing_secret'" />
                        @error('form.secret_password')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="service">Service</label>
                        <select type="text" class="form-select @error('form.ppp_service')
                                is-invalid
                            @enderror" id="service" wire:model="form.ppp_service">
                            <option>Pilih Service</option>
                            @foreach ($form->serviceList as $service)
                            <option value="{{ $service['value'] }}">{{ $service['label'] }}</option>
                            @endforeach
                        </select>
                        @error('form.ppp_service')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row" x-data="{
                        ipType: $wire.entangle('form.ip_type'),
                        localAddress: $wire.entangle('form.local_address'),
                        remoteAddress: $wire.entangle('form.remote_address'),
                    }">
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="ip_type">Type IP</label>
                        <select id="ip_type" class="form-select @error('form.ip_type')
                            is-invalid
                        @enderror" wire:model='form.ip_type' x-model="ipType" x-on:change="
                        if ($event.target.value === 'ip_pool') {
                            localAddress = '';
                            remoteAddress = '';
                        }">
                            @foreach ($form->ipTypeSelect as $ipType)
                            <option value="{{ $ipType['value'] }}">{{ $ipType['label'] }}</option>
                            @endforeach
                        </select>
                        @error('form.ip_type')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="local_address">Local Address</label>
                        <input type="text" class="form-control" :disabled="ipType == 'ip_pool'" x-model='localAddress'
                            wire:model='form.local_address' />
                        @error('form.local_address')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="remote_address">Remote Address</label>
                        <input type="text" class="form-control" :disabled="ipType == 'ip_pool'" x-model="remoteAddress"
                            wire:model='form.remote_address' />
                        @error('form.remote_address')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
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
                    'installed': ['active', 'inactive'],
                    'not_installed': ['inactive']
                },
                activeDate: $wire.entangle('form.active_date'),
                isolirDate: $wire.entangle('form.isolir_date'),
            }">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label" for="installment_status">Status Pemasangan</label>
                        <select type="text" class="form-select @error('form.installment_status')
                                is-invalid
                            @enderror" wire:model="form.installment_status" id="installment_status"
                            x-on:change="updateServiceStatusOptions">
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
                            id="service_status" wire:model="form.service_status" x-model="serviceStatus"
                            :disabled="installmentStatus === 'not_installed'">
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
                            x-on:change="toggleIsolirDate"
                            :disabled="serviceStatus === 'inactive' || installmentStatus == 'not_installed'" />
                        @error('form.active_date')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label" for="isolir_date">Batas Pembayaran / ISOLIR</label>
                        <select type="text" id="isolir_date" class="form-select @error('form.isolir_date')
                            is-invalid
                        @enderror" wire:model='form.isolir_date' x-model="isolirDate" :disabled="isSameDate">
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
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="toggle-date" x-on:click="toggleIsolirDate"
                        x-model="isSameDate">
                    <label class="form-check-label" for="toggle-date">
                        Sesuai Tanggal Aktif Pemakaian
                    </label>
                </div>

                <button class="btn btn-primary" type="submit">
                    <div class="spinner-border" wire:loading>
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span wire:loading.remove>
                        Simpan
                    </span>
                </button>

                <a href="{{ route('customers.index') }}" wire:navigate wire:loading.attr='disabled'
                    class="btn btn-secondary">Batal</a>
            </div>
        </div>
    </form>


    <div class="offcanvas offcanvas-bottom" style="height: 100%" tabindex="-1" id="searchUsername"
        aria-labelledby="searchUsernameLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="searchUsernameLabel">Pilih Username PPOE</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="table-responsive text-nowrap">
                <livewire:secret-table />
            </div>
        </div>
    </div>

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