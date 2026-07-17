@extends('layouts.admin')
@section('title', 'Edit Event')
@section('page-title', 'Edit Event')
@section('content')
    <form method="POST" action="{{ route('admin.events.update', $event->id_event) }}" enctype="multipart/form-data" class="rounded-xl border border-gold/30 bg-white p-6 shadow-glow">@method('PUT') @include('admin.events._form')</form>
@endsection

