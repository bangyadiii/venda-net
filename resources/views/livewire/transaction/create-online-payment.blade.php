<div>
    @section('page-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
    @endsection

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{url('/')}}" class="app-brand-link gap-2">
                                <span
                                    class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
                                <span
                                    class="app-brand-text demo text-body fw-bold uppercase">{{config('app.name')}}</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <p class="mb-4">Ngelak, RT 02 RW 11, Kec. Dampit, KAB. MALANG, Prov. JAWA TIMUR, 65181
                            Malang
                            Telp 089696508086</p>

                        <form id="formAuthentication" class="mb-3" wire:submit.prevent='store'>
                            @csrf
                            <div class="mb-3">
                                <label for="nopel" class="form-label">Nomor Pelanggan</label>
                                <input type="text" class="form-control" wire:model='form.id' id="nopel" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Nama</label>
                                <input type="text" id="name" class="form-control" wire:model='form.customer_name'
                                    disabled />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="telp">Telp/WA</label>
                                <input type="text" id="telp" class="form-control" wire:model='form.phone_number'
                                    disabled />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="telp">Paket</label>
                                <input type="text" id="paket" class="form-control" wire:model='form.plan_name'
                                    disabled />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="tarif">Tarif/Bulan</label>
                                <input type="text" id="tarif" class="form-control" wire:model='form.plan_price'
                                    disabled />
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="status">Status Tagihan</label>
                                <input type="text" id="status" class="form-control" wire:model='form.billStatus'
                                    disabled />
                            </div>

                            @if ($form->billStatus != 'LUNAS')
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary d-grid w-100" wire:loading.attr='disabled'>
                                    <div class="spinner-border" role="status" wire:loading>
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span wire:loading.remove>
                                        Bayar Tagihan
                                    </span>
                                </button>
                            </div>
                            @endif
                            <a href="{{ route('invoices', ['id' => $bill->id]) }}" class="btn btn-primary w-100 mb-3"
                                wire:navigate>Lihat Invoice</a>

                            <a href="{{ route('bill_checks') }}" class="btn btn-secondary w-100"
                                wire:navigate>Kembali</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('page-script')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('midtrans:payment', (event) => {
                snap.pay(event.snapToken, {
                    onSuccess: function(result) {
                        Livewire.dispatch('toast', {title: 'Pembayaran berhasil'});
                        Livewire.navigate("{{ route('invoices', ['id' => $bill->id]) }}");
                    },
                    onPending: function(result) {
                        Livewire.dispatch('toast', {title: 'Pembayaran Pending'});
                    },
                    onError: function(result) {
                        Livewire.dispatch('toast', {title: 'Pembayaran Gagal'});
                    }
                });
            });
        });
    </script>
    @endsection
</div>