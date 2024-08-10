<!DOCTYPE html>

<html class="light-style layout-menu-fixed" data-theme="theme-default" data-assets-path="{{ asset('/assets') . '/' }}"
  data-base-url="{{url('/')}}" data-framework="laravel" data-template="vertical-menu-laravel-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>{{ config('app.name') }}</title>
  <meta name="description"
    content="{{ config('variables.description') ? config('variables.description') : '' }}" />
  <meta name="keywords"
    content="{{ config('variables.keyword') ? config('variables.keyword') : '' }}" />
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  @livewireStyles

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

  <!-- Include Styles -->
  @include('_partials/sections/styles')

  <!-- Include Scripts for customizer, helper, analytics, config -->
  @include('_partials/sections/scriptsIncludes')
</head>

<body>

  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->

  <!-- Include Scripts -->
  @livewireScripts
  @include('_partials/sections/scripts')
  <div x-data="{open: false}" x-show="open" @toast.window="
      console.log($event.detail.type);
      let bgColor = 'linear-gradient(to right, #00b09b, #96c93d)';
      if($event.detail.type === 'success') {
        bgColor = 'linear-gradient(to right, #00b09b, #96c93d)';
      } else if($event.detail.type === 'error') {
        bgColor = 'linear-gradient(to right, #ff5e3a, #ff2e63)';
      } else if($event.detail.type === 'warning') {
        bgColor = 'linear-gradient(to right, #f8b250, #ff5e3a)';
      } else if($event.detail.type === 'info') {
        bgColor = 'linear-gradient(to right, #00b09b, #96c93d)';
      }

      Toastify({
        text: $event.detail.title,
        newWindow: true,
        close: true,
        gravity: 'bottom',
        position: 'right',
        stopOnFocus: true,
        style: {
          background: bgColor,
        }
      }).showToast()">

  </div>
</body>

</html>