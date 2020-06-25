@extends('vendor.layouts.master')

{{-- Page Title --}}
@section('title', 'Dashboard')

{{-- Top Bar --}}
@section('topbar')
@include('vendor.components.topbar')
@endsection

{{-- Side Bar --}}
@section('sidebar')
@include('vendor.components.sidebar')
@endsection