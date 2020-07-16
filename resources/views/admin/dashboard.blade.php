@extends('admin.layouts.master')

{{-- Page Title --}}
@section('title', 'Dashboard')

{{-- Top Bar --}}
@section('topbar')
@include('admin.components.topbar')
@endsection

{{-- Side Bar --}}
@section('sidebar')
@include('admin.components.sidebar')
@endsection

{{-- Main Content --}}
@section('content')

@endsection

{{-- Footer --}}
@section('footer')
@include('admin.components.footer')
@endsection