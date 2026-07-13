@extends('admin.layout')

@section('title', 'Edit API Config — Wargaku Bridging API')
@section('page_title', 'Edit API Config')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Edit API Config</h3>
                <div class="panel-sub">
                    Atur mapping endpoint, status endpoint, dan akses restricted.
                </div>
            </div>

            <a href="{{ route('admin.api-configs.index') }}" class="btn btn-ghost">
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="error-box" style="margin-bottom: 20px;">
                <strong>Validasi gagal.</strong>

                <ul style="margin: 10px 0 0 18px; padding: 0;">
                    @foreach ($errors->all() as $error)
                        <li style="margin-bottom: 4px;">
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.api-configs.update', $config->id) }}">
            @csrf
            @method('PUT')

            <div class="filter-grid">
                <div class="field">
                    <label>Service Name</label>
                    <input type="text" name="service_name" value="{{ old('service_name', $config->service_name) }}"
                        placeholder="Contoh: Login" required>
                </div>

                <div class="field">
                    <label>Method</label>
                    <select name="method" required>
                        <option value="GET" {{ old('method', $config->method) === 'GET' ? 'selected' : '' }}>
                            GET
                        </option>

                        <option value="POST" {{ old('method', $config->method) === 'POST' ? 'selected' : '' }}>
                            POST
                        </option>

                        <option value="PUT" {{ old('method', $config->method) === 'PUT' ? 'selected' : '' }}>
                            PUT
                        </option>

                        <option value="PATCH" {{ old('method', $config->method) === 'PATCH' ? 'selected' : '' }}>
                            PATCH
                        </option>

                        <option value="DELETE" {{ old('method', $config->method) === 'DELETE' ? 'selected' : '' }}>
                            DELETE
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Status Endpoint</label>
                    <select name="status" required>
                        <option value="active" {{ old('status', $config->status) === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ old('status', $config->status) === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="maintenance"
                            {{ old('status', $config->status) === 'maintenance' ? 'selected' : '' }}>
                            Maintenance
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Restricted</label>
                    <select name="is_restricted" required>
                        <option value="0"
                            {{ old('is_restricted', $config->is_restricted ? '1' : '0') === '0' ? 'selected' : '' }}>
                            No
                        </option>

                        <option value="1"
                            {{ old('is_restricted', $config->is_restricted ? '1' : '0') === '1' ? 'selected' : '' }}>
                            Yes
                        </option>
                    </select>
                </div>

                <div class="field" style="grid-column: span 2;">
                    <label>Local Endpoint</label>
                    <input type="text" name="local_endpoint"
                        value="{{ old('local_endpoint', $config->local_endpoint) }}" placeholder="Contoh: /api/v2/login"
                        required>
                </div>

                <div class="field" style="grid-column: span 2;">
                    <label>Target Endpoint</label>
                    <input type="text" name="target_endpoint"
                        value="{{ old('target_endpoint', $config->target_endpoint) }}" placeholder="Contoh: /api/login"
                        required>
                </div>

                <div class="field" style="grid-column: span 4;">
                    <label>Description</label>
                    <textarea name="description" placeholder="Tambahkan deskripsi endpoint...">{{ old('description', $config->description) }}</textarea>
                </div>
                <div class="field">
                    <label>Response Mode</label>
                    <select name="response_mode" required>
                        <option value="standard"
                            {{ old('response_mode', $config->response_mode ?? 'standard') === 'standard' ? 'selected' : '' }}>
                            Standard Bridging Response
                        </option>

                        <option value="legacy"
                            {{ old('response_mode', $config->response_mode ?? 'standard') === 'legacy' ? 'selected' : '' }}>
                            Legacy Mobile Response
                        </option>
                    </select>
                </div>

                <div class="field" style="grid-column: span 4;">
                    <label>Request Mapping</label>
                    <textarea name="request_mapping" placeholder='Contoh: {"pengaduan":"keluhan"}' style="min-height: 120px;">{{ old('request_mapping', $config->request_mapping ? json_encode($config->request_mapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '') }}</textarea>

                    <div class="panel-sub" style="margin-top: 6px;">
                        Digunakan untuk mengubah request dari Wargaku sebelum dikirim ke Media Center.
                    </div>
                </div>

                <div class="field" style="grid-column: span 4;">
                    <label>Response Mapping</label>
                    <textarea name="response_mapping" placeholder='Contoh: {"success":"status","keluhan":"pengaduan"}'
                        style="min-height: 120px;">{{ old('response_mapping', $config->response_mapping ? json_encode($config->response_mapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : '') }}</textarea>

                    <div class="panel-sub" style="margin-top: 6px;">
                        Digunakan untuk mengubah response dari Media Center sebelum dikirim ke Wargaku.
                    </div>
                </div>

            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="{{ route('admin.api-configs.index') }}" class="btn btn-ghost">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Informasi Endpoint</h3>
                <div class="panel-sub">
                    Ringkasan konfigurasi endpoint yang sedang diedit.
                </div>
            </div>

            @php
                $statusClass = match ($config->status) {
                    'active' => 'success',
                    'maintenance' => 'warning',
                    default => 'fail',
                };

                $methodClass = strtolower($config->method);
            @endphp

            <span class="status-pill-table {{ $statusClass }}">
                <span class="d"></span>
                {{ $config->status }}
            </span>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <div class="detail-label">Service</div>
                <div class="detail-value">
                    {{ $config->service_name }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Method</div>
                <div class="detail-value">
                    <span class="method-pill {{ $methodClass }}">
                        {{ $config->method }}
                    </span>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-pill-table {{ $statusClass }}">
                        <span class="d"></span>
                        {{ $config->status }}
                    </span>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Restricted</div>
                <div class="detail-value {{ $config->is_restricted ? 'restricted-yes' : 'restricted-no' }}">
                    {{ $config->is_restricted ? 'Yes' : 'No' }}
                </div>
            </div>

            <div class="detail-card detail-card-wide">
                <div class="detail-label">Local Endpoint</div>
                <div class="detail-value mono">
                    {{ $config->local_endpoint }}
                </div>
            </div>

            <div class="detail-card detail-card-wide">
                <div class="detail-label">Target Endpoint</div>
                <div class="detail-value mono">
                    {{ $config->target_endpoint }}
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Catatan Status Endpoint</h3>
                <div class="panel-sub">
                    Status ini akan memengaruhi request API secara langsung melalui middleware.
                </div>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <div class="detail-label">Active</div>
                <div class="detail-value">
                    Endpoint aktif dan request akan diteruskan ke Media Center.
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Inactive</div>
                <div class="detail-value">
                    Endpoint diblokir oleh Bridging API dengan HTTP status 403.
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Maintenance</div>
                <div class="detail-value">
                    Endpoint sedang maintenance dan request akan diblokir dengan HTTP status 503.
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Restricted</div>
                <div class="detail-value">
                    Jika Yes, request wajib membawa Authorization Bearer Token.
                </div>
            </div>
        </div>
    </div>
@endsection
