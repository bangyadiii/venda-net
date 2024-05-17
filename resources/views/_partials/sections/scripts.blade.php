<!-- BEGIN: Vendor JS-->
<script src="{{ asset(mix('assets/vendor/libs/jquery/jquery.js')) }}" data-navigate-track></script>
<script src="{{ asset(mix('assets/vendor/libs/popper/popper.js')) }}" data-navigate-track></script>
<script src="{{ asset(mix('assets/vendor/js/bootstrap.js')) }}" data-navigate-track></script>
<script src="{{ asset(mix('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')) }}" data-navigate-track>
</script>
<script src="{{ asset(mix('assets/vendor/js/menu.js')) }}" data-navigate-track></script>
<script src="{{asset('assets/js/ui-toasts.js')}}"></script>

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset(mix('assets/js/main.js')) }}"></script>

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->