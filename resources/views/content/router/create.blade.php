@extends('layouts/contentNavbarLayout')

@section('title', 'Add Router')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Router/</span> Create</h4>

<!-- Basic Layout -->
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Router</h5> <small class="text-muted float-end">Default label</small>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">Host</label>
                        <input type="text" class="form-control" id="basic-default-fullname" placeholder="Remote URL" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-company">Username</label>
                        <input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Input your password" />
                    </div>
                    <button type="submit" class="btn btn-primary">Test Connection</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Setting Isolir</h5> <small class="text-muted float-end">Default label</small>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">Auto Isolir ?</label>
                        <input type="text" class="form-control" id="basic-default-fullname" placeholder="Remote URL" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-company">Action Isolir</label>
                        <input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="password">Pilih Profile Isolir</label>
                        <input type="password" id="password" class="form-control" placeholder="Input your password" />
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection