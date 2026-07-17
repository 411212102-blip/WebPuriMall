@extends('layouts.admin')
@section('title', 'Tambah Event')
@section('page-title', 'Tambah Event')
@section('content')
    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">@include('admin.events._form')</form>
@endsection

