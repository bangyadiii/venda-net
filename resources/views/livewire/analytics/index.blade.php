@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="row">
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}" alt="chart success"
                                    class="rounded">
                            </div>

                        </div>
                        <span class="fw-semibold d-block mb-1">Pelanggan</span>
                        <h3 class="card-title mb-2">{{ $customer }}</h3>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/wallet-info.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                        </div>
                        <span>Sudah Bayar</span>
                        <h3 class="card-title text-nowrap mb-1">{{ $paymentComplete }}</h3>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/wallet-info.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                        </div>
                        <span>Belum Bayar</span>
                        <h3 class="card-title text-nowrap mb-1">{{ $customer - $paymentComplete }}</h3>

                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/wallet-info.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                        </div>
                        <span>Suspended</span>
                        <h3 class="card-title text-nowrap mb-1">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Traffic -->
    <div class="col-12 order-2 order-lg-2 mb-4">
        <div class="card p-5">
            <div class="row row-bordered g-0">
                <div class="col-md-8">
                    <h5 class="card-header m-0 me-2">Traffic</h5>
                    <div id="totalRevenueChart" class="px-2"></div>
                </div>
                <div class="col-md-4 pt-3">
                    <div class="row">
                        <div id="growthChart"></div>
                        <div class="text-center fw-medium pt-3 mb-2">CPU Load</div>
                    </div>

                    <div class="row">
                        <div id="growthChart1"></div>
                        <div class="text-center fw-medium pt-3 mb-2">Memory Load</div>
                    </div>

                    <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-primary p-2"><i
                                        class="bx bx-dollar text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Board Name</small>
                                <h6 class="mb-0">x86 ( x86_64 )</h6>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-2">
                                <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                                <small>Version</small>
                                <h6 class="mb-0">7.14.2 (stable)</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}"
                                        alt="chart success" class="rounded">
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">PPP Secret</span>
                            <h3 class="card-title mb-2">{{ count($secret) ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}"
                                        alt="chart success" class="rounded">
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">PPP Online</span>
                            <h3 class="card-title mb-2">{{ count($onlineSecret) ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-12 my-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{asset('assets/img/icons/unicons/chart-success.png')}}"
                                        alt="chart success" class="rounded">
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">PPP Offline</span>
                            {{-- <h3 class="card-title mb-2">{{ ($secret && $onlineSecret) ? $secret - $onlineSecret : 0 }}</h3> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Traffic -->

</div>