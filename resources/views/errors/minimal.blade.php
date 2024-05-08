@extends('layouts/commonMaster' )

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-misc.css')}}">
@endsection


@section('layoutContent')

<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <h2>@yield('code')</h2>
        <h2 class="mb-2 mx-2">@yield('title')</h2>
        <p class="mb-4 mx-2">@yield('description')</p>
        <a href="{{url('/')}}" class="btn btn-primary">Back to home</a>
        <div class="mt-3">
            <img src="{{asset('assets/img/illustrations/page-misc-error-light.png')}}" alt="page-misc-error-light"
                width="500" class="img-fluid">
        </div>
    </div>
</div>
@endsection