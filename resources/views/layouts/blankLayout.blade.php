@extends('layouts/commonMaster' )

@section('layoutContent')


<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    {{ $slot }}

</div>
<!-- / Content -->

@endsection