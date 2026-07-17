@extends('layouts.admin')
@section('title', 'Tambah Fasilitas')
@section('page-title', 'Tambah Fasilitas')
@section('content')
    <form method="POST" action="{{ route('admin.fasilitas.store') }}" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">@include('admin.fasilitas._form')</form>
@endsection

