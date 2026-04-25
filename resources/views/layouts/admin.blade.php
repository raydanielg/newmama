@extends('layouts.app')

@section('content')
<div class="admin-wrapper">
    {{-- Admin Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main Content --}}
    <div class="admin-main">
        @include('admin.partials.header')

        <div class="admin-content-area">
            @yield('admin-content')
        </div>
    </div>
</div>
@endsection
