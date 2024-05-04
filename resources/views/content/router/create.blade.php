@extends('layouts/contentNavbarLayout')

@section('title', 'Add Router')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Router/</span> Create</h4>

<!-- Basic Layout -->
<form action="{{ route("router-settings.store") }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Router</h5> 
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="host">Host</label>
                        <input type="text" class="form-control" name="host" id="host" placeholder="Remote URL"
                            required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Input your password"/>
                    </div>
                    <button class="btn btn-primary">Test Connection</button>
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
                        <select class="form-select" id="auto_isolir" name="auto_isolir">
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="isolir_action">Action Isolir</label>
                        <select class="form-select" id="isolir_action" name="isolir_action">
                            <option value="change_profile">UBAH SECRET PROFILE</option>
                            <option value="disabled_secret">DISABLE SECRET</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="isolir_profile">Pilih Profile Isolir</label>
                        <select class="form-select" id="isolir_profile" name="isolir_profile">
                            {{-- @TODO: dinamik --}}
                            <option value="*1">1 MB</option>
                            <option value="*2">DISABLE</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button class="btn btn-secondary">Batal</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection