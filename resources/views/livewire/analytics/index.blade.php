<div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="row">
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">Pelanggan</span>
                                <h3 class="card-title mb-2">{{ $totalCustomer }}
                                </h3>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/users.png')}}" alt="chart success"
                                    class="rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">Sudah Bayar</span>
                                <h3 class="card-title text-nowrap mb-1">{{ $paymentComplete }}
                                    <span class="fs-5 text-secondary">(+{{ currency($totalBayar) }})</span>
                                </h3>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/coin.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">Belum Bayar</span>
                                <h3 class="card-title text-nowrap mb-1">{{ $totalCustomer - $paymentComplete }}
                                    <span class="fs-5 text-secondary">(-{{ currency($totalBelumBayar) }})</span>
                                </h3>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/coin-warning.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">Suspended</span>
                                <h3 class="card-title text-nowrap mb-1">{{ $suspended }}</h3>
                            </div>
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('assets/img/icons/unicons/warning.png')}}" alt="Credit Card"
                                    class="rounded">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Traffic -->
    <div class="col-12 order-2 order-lg-2 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="row p-1">
                    <div class="col-md-4">
                        <label class="form-label" for="router">Router</label>
                        <select type="text" id="router" class="form-select @error('selectedRouterId')
                        is-invalid
                    @enderror" wire:model.live='selectedRouterId' wire:loading.attr='disabled'>
                            @foreach ($routers as $router)
                            <option value="{{ $router->id }}">{{ $router->host }}</option>
                            @endforeach
                        </select>
                        @error('selectedRouterId')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="interface">Interface</label>
                        <select id="interface" class="form-select @error('interface')
                        is-invalid
                    @enderror" wire:model.live='interface' wire:loading.attr='disabled'>
                            @foreach ($interfaces as $interface)
                            <option value="{{ $interface }}">{{ $interface }}</option>
                            @endforeach
                        </select>
                        @error('interface')
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="row row-bordered g-0">
                    <div class="col-md-9 col-xl-8" x-data="trafficChartData()" x-init="initTrafficChart()"
                        wire:poll.3s='fetchTrafficData' wire:ignore>
                        <h5 class="card-header m-0 me-2">Traffic</h5>
                        <div wire:loading wire:target="selectedRouterId, interface" class="loading-overlay">
                            <output class="spinner-border text-primary">
                                <span class="visually-hidden">Loading...</span>
                            </output>
                        </div>
                        <div id="trafficMonitorChart" class="px-2"></div>
                    </div>
                    <div class="col-md-3 col-xl-4 pt-3 row">
                        <div class="col-6 col-md-12 col-lg-12 col-xl-6" wire:ignore x-data="cpuChart()"
                            x-init="initCpuChart()" wire:poll.3s='fetchRouterResource'>
                            <div wire:loading wire:target="selectedRouterId" class="loading-overlay">
                                <output class="spinner-border text-primary">
                                    <span class="visually-hidden">Loading...</span>
                                </output>
                            </div>
                            <div id="cpuChart"></div>
                            <div class="text-center fw-medium pt-3 mb-2">CPU Load</div>
                        </div>

                        <div class="col-6 col-md-12 col-lg-12 col-xl-6" wire:ignore x-data="memoryChart()"
                            x-init="initMemoryChart()">
                            <div wire:loading wire:target="selectedRouterId" class="loading-overlay">
                                <output class="spinner-border text-primary">
                                    <span class="visually-hidden">Loading...</span>
                                </output>
                            </div>
                            <div id="memoryChart"></div>
                            <div class="text-center fw-medium pt-3 mb-2">Memory Load</div>
                        </div>

                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                            <div class="d-flex">
                                <div class="me-2">
                                    <span class="badge bg-label-primary p-2"><i class='bx bx-chip'></i></span>
                                </div>
                                <div class="d-flex flex-column">
                                    <small>Board Name</small>
                                    <h6 class="mb-0">{{ $boardName }}</h6>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-2">
                                    <span class="badge bg-label-info p-2">
                                        <i class='bx bx-calendar-check'></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <small>Version</small>
                                    <h6 class="mb-0">{{ $version }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" wire:poll.3s='fetchSecrets'>
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
                                <h3 class="card-title mb-2">{{ $secret }}</h3>
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
                                <h3 class="card-title mb-2">{{ $onlineSecret }}</h3>
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
                                <h3 class="card-title mb-2">{{  $secret - $onlineSecret  }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Traffic -->
    @section('page-script')
    <script src="{{asset('assets/js/dashboards-analytics.js')}}" data-navigate-track></script>
    <script data-navigate-track>
        function cpuChart(){
            return {
                cpuChart: null,
                initCpuChart() {
                    this.cpuChart = new ApexCharts(document.querySelector("#cpuChart"), {
                        ...cpuChartOption
                    });
                    this.cpuChart.render();
                    Livewire.on('updateCpuChart', data => {
                        this.cpuChart.updateSeries(data);
                    });
                }
            }
        }
        function memoryChart() {
            return {
                memoryChart: null,
                initMemoryChart() {
                    this.memoryChart = new ApexCharts(document.querySelector("#memoryChart"), {
                        ...memoryChartOption
                    });
                    this.memoryChart.render();
                    Livewire.on('updateMemoryChart', response => {
                        const data = response[0];
                        const percentage = Math.round((data['total'] - data['free']) / data['total'] * 100);
                        this.memoryChart.updateSeries([percentage]);
                    });
                }
            }
        }
        function trafficChartData() {
            return {
                chart: null,
                initTrafficChart() {
                    this.chart = new ApexCharts(document.querySelector("#trafficMonitorChart"), {
                        series: [
                            {
                                name: 'Rx',
                                data: @json(array_map('convertSpeed', $trafficData['rx']))
                            },
                            {
                                name: 'Tx',
                                data: @json(array_map('convertSpeed', $trafficData['tx']))
                            }
                        ],
                        chart: {
                            type: 'area',
                            height: 350,
                            zoom: {
                                enabled: false
                            },
                            animations: {
                                enabled: true,
                                easing: 'linear',
                                dynamicAnimation: {
                                    speed: 1000
                                }
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            formatter:function (value) {
                                if (value >= 1000000) {
                                    return (value / 1000000).toFixed(2) + ' Mbps';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(2) + ' kbps';
                                }

                                return value + ' bps';
                                
                            },
                        },
                        colors: [config.colors.primary, config.colors.info],
                        stroke: {
                            curve: 'smooth',
                            width: 3,
                        },
                        xaxis: {
                            categories: @json($trafficData['timestamps']),
                            type: 'category',
                        },
                        yaxis: {
                            title: {
                                text: 'Throughput'
                            },
                            labels: {
                                formatter: function (value) {
                                    if (value >= 1000000) {
                                        return (value / 1000000).toFixed(2) + ' Mbps';
                                    } else if (value >= 1000) {
                                        return (value / 1000).toFixed(2) + ' kbps';
                                    }

                                    return value + ' bps';
                                    
                                }
                            }
                        }
                    });
                    this.chart.render();

                    Livewire.on('updateTrafficChart', datas => {
                        const data = datas[0];
                        this.chart.updateSeries([
                            { data: data.rx },
                            { data: data.tx }
                        ]);
                        this.chart.updateOptions({
                            xaxis: {
                                categories: data.timestamps
                            }
                        });
                    });
                }
            }
        }
    </script>
    @endsection
</div>