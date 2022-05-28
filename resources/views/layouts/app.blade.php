@extends('layouts.master')

@section('vendor_css')
    @stack('css')
    @yield('css')
@stop

@section('body-class', 'hold-transition sidebar-mini layout-fixed')

@section('body')
    <div class="wrapper">

        {{-- Top Navbar --}}
        @include('partials.navbar')

        {{-- Left Main Sidebar --}}
        @include('partials.sidebar')

        {{-- Content Wrapper --}}
        @include('partials.content')

        {{-- Footer --}}
        @include('partials.footer')

        {{-- Right Control Sidebar --}}
        @include('partials.control')

    </div>
    @yield('components')
@stop

@section('vendor_js')
    @stack('js')
    @yield('js')
@stop



