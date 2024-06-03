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
                    <form wire:submit='store' x-data="{
                        enabled: $wire.entangle('form.enabled'),
                        template: $wire.entangle('form.template'),
                        changed: false,
                    }" x-init="$watch('enabled', value => {
                        changed = true;
                    }); $watch('template', value => {
                        changed = true;
                    });">
                        @csrf
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="flexSwitchCheckDefault" wire:model='form.enabled' x-model="enabled">
                                <label class="form-check-label" for="flexSwitchCheckDefault">Otomatis mengirim
                                    notifikasi pembayaran ke pelanggan?</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="format">Template Notifikasi</label>
                            <textarea id="format" type="text" class="form-control @error('form.template')
                                is-invalid
                            @enderror" wire:model='form.template' rows="13" :disabled="!enabled"></textarea>

                            @error('form.template')
                            <div class="error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        Gunakan variabel berikut untuk mengganti data pelanggan:
                        <ul>
                            <li>
                                [NOPEL] = Nomor Pelanggan
                            </li>
                            <li>
                                [NAMA] = Nama Pelanggan
                            </li>
                            <li>
                                [PHONE] = Nomor Wa Pelanggan
                            </li>
                            <li>
                                [ALAMAT] = Alamat Pelanggan
                            </li>
                            <li>
                                [PAKET] = Nama Paket Pelanggan
                            </li>
                            <li>
                                [TARIFPAKET] = Tarif Paket Pelanggan
                            </li>
                            <li>
                                [DISKON] = Diskon Paket Pelanggan
                            </li>
                            <li>
                                [TAGIHAN] =Total Tagihan perBulan
                            </li>
                            <li>
                                [ISOLIR] = Tanggal Isolir Pelanggan
                            </li>
                            <li>
                                [BANK] = data Bank Perusahaan
                            </li>
                        </ul>

                        <button type="submit" class="btn btn-primary" wire:loading.attr='disabled' :disabled="!changed">
                            <output class="spinner-border" wire:loading>
                                <span class="visually-hidden">Loading...</span>
                            </output>
                            <span wire:loading.remove>
                                Simpan
                            </span>
                        </button>
                        <button type="button" class="btn btn-secondary" wire:loading.attr='disabled' wire:click='cancel' x-show="changed" x-transition>
                            <output class="spinner-border" wire:loading>
                                <span class="visually-hidden">Loading...</span>
                            </output>
                            <span wire:loading.remove>
                                Batal Simpan
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>