@extends('layouts.admin')
@section('title', 'Edit Hadiah')
@section('page-title', 'Edit Hadiah')
@section('content')
    <form method="POST" action="{{ route('admin.hadiah.update', $hadiah->id_hadiah) }}" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">@method('PUT') @include('admin.hadiah._form')</form>
@endsection

