<!-- laravel style -->
<script src="{{ asset('assets/vendor/js/helpers.js') }}" data-navigate-track></script>

<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('assets/js/config.js') }}" data-navigate-track></script>
{{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.sandbox_client_key') }}">
</script> --}}
@midtransSnapScripts

<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->