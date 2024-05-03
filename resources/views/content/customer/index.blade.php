@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Data Pelanggan</span>
</h4>

<div class="row">
    <!-- Striped Rows -->
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a href="{{ route('customers.create') }}" type="button" class="btn btn-primary">Tambah Data</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nomor Pelanggan</th>
                            <th>Nama</th>
                            <th>Telp / WA</th>
                            <th>Secret</th>
                            <th>Paket</th>
                            <th>Tarif</th>
                            <th>Discount</th>
                            <th>Total Tarif</th>
                            <th>Aktif</th>
                            <th>Tgl. Isolir</th>
                            <th>Isolir</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <tr>
                            <td>Angular Project</td>
                            <td>Yanto</td>
                            <td>08931293182</td>
                            <td>Misalkan</td>
                            <td>1 MB</td>

                            <td>Rp 150.000</td>
                            <td>Rp 10.000</td>
                            <td>Rp 140.000</td>
                            <td>YA</td>
                            <td>08-02-2023</td>
                            <td>YA</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('customers.edit', 1) }}"><i
                                                class="bx bx-edit-alt me-1"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i
                                                class="bx bx-trash me-1"></i>
                                            Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection