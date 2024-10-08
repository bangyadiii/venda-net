<div>
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Transaksi /</span> Tambah</h4>

    <!-- Basic Layout -->
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Transaksi</h5>
                </div>
                <div class="card-body">
                    <form wire:submit='store'>
                        <input type="number" wire:model='form.bill_id' hidden>
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-8">
                                <label class="form-label" for="customer_id">Pilih Pelanggan</label>
                                <select id="customer_id" class="form-select" wire:model.live='customer_id'>
                                    <option value>Pilih Pelanggan</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer['id'] }}">{{ $customer['customer_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="telp">Telp/WA</label>
                                <input type="text" class="form-control" disabled wire:model='phone_number' />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-8">
                                <label class="form-label" for="alamat">Alamat</label>
                                <textarea type="text" class="form-control" disabled wire:model='address'></textarea>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="name">User PPPoE</label>
                                <input type="text" class="form-control" disabled wire:model='secret_username' />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label" for="paket">Paket</label>
                                    <input wire:model='plan_name' type="text" class="form-control" disabled />
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="pembayaran">Pembayaran</label>
                                        <input type="text" class="form-control" disabled wire:model='isolir_month'>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="name">Untuk Periode Pemakaian</label>
                                        <input type="text" class="form-control" disabled wire:model='period' />
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="tarif">Tarif Paket</label>
                                    <input value="{{ $plan_price ? currency($plan_price) : null }}" type="text"
                                        class="form-control" disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="diskon">Diskon</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" wire:model.live='form.discount' />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="nominal">Nominal</label>
                                    <input type="text" class="form-control" disabled value="{{ currency($nominal) }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="ppn">PPN (%)</label>
                                    <input type="text" class="form-control" disabled wire:model='form.tax_rate'></input>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="total_ppn">Total PPN</label>
                                    <input type="text" class="form-control" disabled
                                        value='{{ currency($total_ppn) }}' />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="tagihan">Total Tagihan</label>
                                    <input type="text" class="form-control" disabled
                                        value='{{ currency($grand_total) ?? 0 }}' />
                                </div>
                            </div>

                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr='disabled'>
                                <output class="spinner-border" wire:loading>
                                    <span class="visually-hidden">Loading...</span>
                                </output>
                                <span wire:loading.remove>
                                    Bayar
                                </span>
                            </button>
                            <a class="btn btn-secondary" wire:navigate href="{{ route('transactions.index') }}"
                                wire:loading.attr='disabled'>Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>