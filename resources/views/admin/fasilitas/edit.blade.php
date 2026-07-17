@extends('layouts.admin')
@section('title', 'Edit Fasilitas')
@section('page-title', 'Edit Fasilitas')
@section('content')
    <form method="POST" action="{{ route('admin.fasilitas.update', $fasilitas->id_fasilitas) }}" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">@method('PUT') @include('admin.fasilitas._form')</form>
@endsection

