@extends('layouts.admin')

@section('title', 'Tambah Tenant')
@section('page-title', 'Tambah Tenant')

@section('content')
    <form method="POST" action="{{ route('admin.tenants.store') }}" enctype="multipart/form-data" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">
        @include('admin.tenants._form')
    </form>
@endsection

