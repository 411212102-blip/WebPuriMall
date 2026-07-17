@extends('layouts.admin')

@section('title', 'Edit Tenant')
@section('page-title', 'Edit Tenant')

@section('content')
    <form method="POST" action="{{ route('admin.tenants.update', $tenant->id_tenant) }}" enctype="multipart/form-data" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">
        @method('PUT')
        @include('admin.tenants._form')
    </form>
@endsection

