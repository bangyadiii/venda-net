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
                        <form id="formAuthentication" class="mb-3" wire:submit.prevent='store'>
                            @csrf
                            <div class="mb-3">
                                <label for="nopel" class="form-label">Nomor Pelanggan</label>
                                <input type="text" class="form-control  @error('form.customer_id')
                                    is-invalid
                                @enderror" wire:model='form.customer_id' id=" nopel"
                                    placeholder="Masukan Nomor Pelanggan Anda">
                                @error('form.customer_id')
                                <div class="error">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary d-grid w-100" wire:loading.attr='disabled'>
                                    <div class="spinner-border" role="status" wire:loading>
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span wire:loading.remove>
                                        Cek Tagihan
                                    </span>
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>