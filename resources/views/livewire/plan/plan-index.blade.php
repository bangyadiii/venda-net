@section('page-script')
<script src="{{asset('assets/js/pages-account-settings-account.js')}}"></script>
@endsection

<div>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Plan Settings</span>
    </h4>

    <div class="row">
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="text-end">
                    <a href="{{ route('plans.create') }}" wire:navigate type="button" class="btn btn-primary">Tambah
                        Paket</a>
                    <button class="btn btn-secondary">Sinkron Paket</button>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Paket</th>
                                <th>Kecepatan</th>
                                <th>Tarif</th>
                                <th>Router</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td>{{ $plan->speed_limit }}</td>

                                <td>Rp. {{ $plan->price }}</td>
                                <td>{{ $plan->router->host }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i
                                                class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('plans.edit', $plan->id) }}"><i
                                                    class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <button class="dropdown-item" wire:click='delete({{ $plan->id }})'><i
                                                    class="bx bx-trash me-1"></i>
                                                Delete</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>


                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </div>
</div>