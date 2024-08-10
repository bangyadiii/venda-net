<!-- laravel style -->
<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('assets/js/config.js') }}" ></script>

@midtransSnapScripts

<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>

<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->