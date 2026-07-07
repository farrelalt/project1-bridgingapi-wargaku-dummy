@extends('admin.layout')

@section('title', 'Tambah API Config — Wargaku Bridging API')
@section('page_title', 'Tambah API Config')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Tambah API Config</h3>
                <div class="panel-sub">
                    Tambahkan endpoint baru yang akan diteruskan dari Bridging API ke Media Center.
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

        <form method="POST" action="{{ route('admin.api-configs.store') }}">
            @csrf

            <div class="filter-grid">
                <div class="field">
                    <label>Service Name</label>
                    <input
                        type="text"
                        name="service_name"
                        value="{{ old('service_name') }}"
                        placeholder="Contoh: Berita List"
                        required
                    >
                </div>

                <div class="field">
                    <label>Method</label>
                    <select name="method" required>
                        <option value="GET" {{ old('method') === 'GET' ? 'selected' : '' }}>
                            GET
                        </option>

                        <option value="POST" {{ old('method') === 'POST' ? 'selected' : '' }}>
                            POST
                        </option>

                        <option value="PUT" {{ old('method') === 'PUT' ? 'selected' : '' }}>
                            PUT
                        </option>

                        <option value="PATCH" {{ old('method') === 'PATCH' ? 'selected' : '' }}>
                            PATCH
                        </option>

                        <option value="DELETE" {{ old('method') === 'DELETE' ? 'selected' : '' }}>
                            DELETE
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Status Endpoint</label>
                    <select name="status" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>
                            Maintenance
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Restricted</label>
                    <select name="is_restricted" required>
                        <option value="0" {{ old('is_restricted', '0') === '0' ? 'selected' : '' }}>
                            No
                        </option>

                        <option value="1" {{ old('is_restricted') === '1' ? 'selected' : '' }}>
                            Yes
                        </option>
                    </select>
                </div>

                <div class="field" style="grid-column: span 2;">
                    <label>Local Endpoint</label>
                    <input
                        type="text"
                        name="local_endpoint"
                        value="{{ old('local_endpoint') }}"
                        placeholder="Contoh: /api/v2/berita"
                        required
                    >
                </div>

                <div class="field" style="grid-column: span 2;">
                    <label>Target Endpoint</label>
                    <input
                        type="text"
                        name="target_endpoint"
                        value="{{ old('target_endpoint') }}"
                        placeholder="Contoh: /api/berita"
                        required
                    >
                </div>

                <div class="field" style="grid-column: span 4;">
                    <label>Description</label>
                    <textarea
                        name="description"
                        placeholder="Contoh: Mengambil daftar berita dari Media Center."
                    >{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">
                    Simpan Endpoint Baru
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
                <h3 class="panel-title">Contoh Pengisian</h3>
                <div class="panel-sub">
                    Gunakan pola ini agar endpoint baru bisa langsung ditangkap oleh dynamic forwarder.
                </div>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <div class="detail-label">Service Name</div>
                <div class="detail-value">
                    Berita List
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Method</div>
                <div class="detail-value">
                    <span class="method-pill get">
                        GET
                    </span>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-pill-table success">
                        <span class="d"></span>
                        active
                    </span>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Restricted</div>
                <div class="detail-value restricted-no">
                    No
                </div>
            </div>

            <div class="detail-card detail-card-wide">
                <div class="detail-label">Local Endpoint</div>
                <div class="detail-value mono">
                    /api/v2/berita
                </div>
            </div>

            <div class="detail-card detail-card-wide">
                <div class="detail-label">Target Endpoint</div>
                <div class="detail-value mono">
                    /api/berita
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Catatan Penting</h3>
                <div class="panel-sub">
                    Endpoint baru harus mengikuti aturan agar aman dan bisa diteruskan.
                </div>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-card detail-card-wide">
                <div class="detail-label">Local Endpoint</div>
                <div class="detail-value">
                    Harus diawali dengan <span class="mono">/api/v2/</span>.
                    Contoh: <span class="mono">/api/v2/berita</span>
                </div>
            </div>

            <div class="detail-card detail-card-wide">
                <div class="detail-label">Target Endpoint</div>
                <div class="detail-value">
                    Harus diawali dengan <span class="mono">/api/</span>.
                    Contoh: <span class="mono">/api/berita</span>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Active</div>
                <div class="detail-value">
                    Endpoint bisa digunakan normal.
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Inactive</div>
                <div class="detail-value">
                    Endpoint diblokir dengan status 403.
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Maintenance</div>
                <div class="detail-value">
                    Endpoint diblokir dengan status 503.
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Restricted</div>
                <div class="detail-value">
                    Jika Yes, request wajib membawa Bearer Token.
                </div>
            </div>
        </div>
    </div>
@endsection