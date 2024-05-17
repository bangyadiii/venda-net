@extends('layouts/commonMaster' )


@php
/* Display elements */
$contentNavbar = true;
$containerNav = ($containerNav ?? 'container-xxl');
$isNavbar = ($isNavbar ?? true);
$isMenu = ($isMenu ?? true);
$isFlex = ($isFlex ?? false);
$isFooter = ($isFooter ?? true);

/* HTML Classes */
$navbarDetached = 'navbar-detached';

/* Content classes */
$container = ($container ?? 'container-xxl');

@endphp

@section('layoutContent')
<div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
  <div class="layout-container">

    @if ($isMenu)
    @include('_partials/sections/menu/verticalMenu')
    @endif


    <!-- Layout page -->
    <div class="layout-page">
      <!-- BEGIN: Navbar-->
      @if ($isNavbar)
      @include('_partials/sections/navbar/navbar')
      @endif
      <!-- END: Navbar-->


      <!-- Content wrapper -->
      <div class="content-wrapper">

        <!-- Content -->
        <div class="{{$container}} flex-grow-1 container-p-y">

          {{ $slot }}

        </div>
        <!-- / Content -->

        <!-- Footer -->
        @if ($isFooter)
        @include('_partials/sections/footer/footer')
        @endif
        <!-- / Footer -->
        <div class="content-backdrop fade"></div>
      </div>
      <!--/ Content wrapper -->
    </div>
    <!-- / Layout page -->
  </div>

  @if ($isMenu)
  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>
  @endif
  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>
</div>
@endsection