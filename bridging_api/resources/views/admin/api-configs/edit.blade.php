@extends('admin.layout')

@section('title', 'Edit API Config — Wargaku Bridging API')
@section('page_title', 'Edit API Config')

@section('content')
    <div class="form-card">
        <form method="POST" action="{{ route('admin.api-configs.update', $config->id) }}">
            @csrf
            @method('PUT')

            <label>Service Name</label>
            <input type="text" value="{{ $config->service_name }}" disabled>

            <label>Local Endpoint</label>
            <input type="text" value="{{ $config->local_endpoint }}" disabled>

            <label>Method</label>
            <input type="text" value="{{ $config->method }}" disabled>

            <label>Target Endpoint</label>
            <input type="text" name="target_endpoint" value="{{ old('target_endpoint', $config->target_endpoint) }}">

            <label>Status</label>
            <select name="status">
                <option value="active" {{ $config->status === 'active' ? 'selected' : '' }}>active</option>
                <option value="inactive" {{ $config->status === 'inactive' ? 'selected' : '' }}>inactive</option>
                <option value="maintenance" {{ $config->status === 'maintenance' ? 'selected' : '' }}>maintenance</option>
            </select>

            <div class="checkbox-row">
                <input type="checkbox" name="is_restricted" value="1" {{ $config->is_restricted ? 'checked' : '' }}>
                <span>Restricted endpoint</span>
            </div>

            <label>Description</label>
            <textarea name="description">{{ old('description', $config->description) }}</textarea>

            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="{{ route('admin.api-configs.index') }}">Kembali</a>
        </form>
    </div>
@endsection