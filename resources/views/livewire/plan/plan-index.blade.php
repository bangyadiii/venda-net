<div>
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Setting Paket</span>
    </h4>

    <div class="row">
        <!-- Striped Rows -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2 mb-2">
                    <a href="{{ route('plans.create') }}" wire:navigate type="button" class="btn btn-primary">Tambah
                        Paket</a>
                    <div class="dropdown" wire:loading.attr='disabled'>
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <output class="spinner-border me-2 spinner-border-sm" wire:loading>
                                <span class="visually-hidden">Loading...</span>
                            </output>
                            Import Paket
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($routers as $router)
                            <li>
                                <button class="dropdown-item" wire:click='syncPlan({{ $router->id }})'
                                    wire:confirm='Apakah kamu yakin ingin import profile dari {{ $router->host }}?' wire:loading.attr='disabled'>
                                    {{ $router->host }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
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

                                <td>{{ currency($plan->price) }}</td>
                                <td>{{ $plan->router->host }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown"><i
                                                class="bx bx-dots-vertical-rounded"></i></button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" wire:navigate
                                                href="{{ route('plans.edit', $plan->id) }}"><i
                                                    class="bx bx-edit-alt me-1"></i> Edit</a>
                                            <button class="dropdown-item" wire:click='delete({{ $plan->id }})'
                                                wire:confirm='Are you sure to delete this?'><i
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