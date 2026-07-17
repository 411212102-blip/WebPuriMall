@extends('layouts.admin')
@section('title', 'Tambah Hadiah')
@section('page-title', 'Tambah Hadiah')
@section('content')
    <form method="POST" action="{{ route('admin.hadiah.store') }}" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">@include('admin.hadiah._form')</form>
@endsection

