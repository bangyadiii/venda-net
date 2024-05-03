@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Paket')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Paket/</span> Tambah</h4>

<!-- Basic Layout -->
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Paket</h5> <small class="text-muted float-end">Default label</small>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label" for="name">Nama Paket</label>
                        <input type="text" class="form-control" id="name" placeholder="Nama Paket" name="name" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="speed">Kecepatan</label>
                        <input type="text" class="form-control" name="speed" id="speed" placeholder="1" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tarif">Tarif per Bulan</label>
                        <input type="text" id="tarif" class="form-control" placeholder="Masukan Tarif per bulan" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="tarif">Router</label>
                        <input type="text" id="tarif" class="form-control" placeholder="Pilih Router" />
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button class="btn btn-secondary">Batal</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection