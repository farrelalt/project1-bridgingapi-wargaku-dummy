@extends('admin.layout')

@section('title', 'API Configs — Wargaku Bridging API')
@section('page_title', 'API Configs')

@section('content')
    <div class="panel api-config-panel">
        <div class="api-config-panel-head">
            <div class="api-config-head-title">
                <h3 class="panel-title">Daftar API Configs</h3>
                <div class="panel-sub">
                    Mapping endpoint lokal Bridging API ke endpoint Media Center
                </div>
            </div>

            <div class="api-config-head-controls">
                <span class="api-config-head-btn api-config-count-btn">
                    {{ $configs->total() }} endpoint
                </span>

                <a
                    href="{{ route('admin.api-configs.create') }}"
                    class="api-config-head-btn api-config-add-btn"
                >
                    Tambah Endpoint
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.api-configs.index') }}" style="margin-bottom: 22px;">
            <div class="filter-grid">
                <div class="field" style="grid-column: span 2;">
                    <label>Keyword</label>
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Cari service, local endpoint, target endpoint..."
                        value="{{ request('keyword') }}"
                    >
                </div>

                <div class="field">
                    <label>Method</label>
                    <select name="method">
                        <option value="">Semua method</option>

                        <option value="GET" {{ request('method') === 'GET' ? 'selected' : '' }}>
                            GET
                        </option>

                        <option value="POST" {{ request('method') === 'POST' ? 'selected' : '' }}>
                            POST
                        </option>

                        <option value="PUT" {{ request('method') === 'PUT' ? 'selected' : '' }}>
                            PUT
                        </option>

                        <option value="PATCH" {{ request('method') === 'PATCH' ? 'selected' : '' }}>
                            PATCH
                        </option>

                        <option value="DELETE" {{ request('method') === 'DELETE' ? 'selected' : '' }}>
                            DELETE
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua status</option>

                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>
                            Maintenance
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Restricted</label>
                    <select name="is_restricted">
                        <option value="">Semua restricted</option>

                        <option value="1" {{ request('is_restricted') === '1' ? 'selected' : '' }}>
                            Yes
                        </option>

                        <option value="0" {{ request('is_restricted') === '0' ? 'selected' : '' }}>
                            No
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Per Page</label>
                    <select name="per_page">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>
                            10 data
                        </option>

                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>
                            20 data
                        </option>

                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>
                            50 data
                        </option>
                    </select>
                </div>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.api-configs.index') }}" class="btn btn-ghost">
                    Reset Filter
                </a>
            </div>
        </form>

        <div class="panel-head" style="margin-bottom: 14px;">
            <div>
                <h3 class="panel-title">Hasil API Configs</h3>
                <div class="panel-sub">
                    Menampilkan {{ $configs->firstItem() ?? 0 }} - {{ $configs->lastItem() ?? 0 }}
                    dari {{ $configs->total() }} endpoint
                </div>
            </div>

            <span class="badge">
                Page {{ $configs->currentPage() }} dari {{ $configs->lastPage() }}
            </span>
        </div>

        @if ($configs->count() > 0)
            <div class="rows">
                <div class="row-head config-head">
                    <div>NO</div>
                    <div>SERVICE</div>
                    <div>LOCAL</div>
                    <div>TARGET</div>
                    <div>METHOD</div>
                    <div>STATUS</div>
                    <div>RESTRICTED</div>
                    <div>AKSI</div>
                </div>

                @foreach ($configs as $config)
                    @php
                        $methodClass = strtolower($config->method);

                        $statusClass = match ($config->status) {
                            'active' => 'success',
                            'maintenance' => 'warning',
                            default => 'fail',
                        };

                        $rowNumber = ($configs->firstItem() ?? 1) + $loop->index;
                    @endphp

                    <div class="row-card config-card">
                        <div>
                            {{ $rowNumber }}
                        </div>

                        <div>
                            <b>{{ $config->service_name }}</b>
                        </div>

                        <div class="mono">
                            {{ $config->local_endpoint }}
                        </div>

                        <div class="mono">
                            {{ $config->target_endpoint }}
                        </div>

                        <div>
                            <span class="method-pill {{ $methodClass }}">
                                {{ $config->method }}
                            </span>
                        </div>

                        <div>
                            <span class="status-pill-table {{ $statusClass }}">
                                <span class="d"></span>
                                {{ ucfirst($config->status) }}
                            </span>
                        </div>

                        <div>
                            @if ($config->is_restricted)
                                <span class="restricted-yes">
                                    Yes
                                </span>
                            @else
                                <span class="restricted-no">
                                    No
                                </span>
                            @endif
                        </div>

                        <div>
                            <div class="config-action-row">
                                <a
                                    href="{{ route('admin.api-configs.edit', $config->id) }}"
                                    class="edit-btn"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.api-configs.destroy', $config->id) }}"
                                    onsubmit="return openWargakuDeleteModal(this, 'api-config', @js($config->service_name))"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($configs->hasPages())
                <div class="pagination-simple">
                    <div>
                        @if ($configs->onFirstPage())
                            <span class="btn btn-ghost">
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $configs->previousPageUrl() }}" class="btn btn-ghost">
                                Sebelumnya
                            </a>
                        @endif
                    </div>

                    <span class="badge">
                        Page {{ $configs->currentPage() }} dari {{ $configs->lastPage() }}
                    </span>

                    <div>
                        @if ($configs->hasMorePages())
                            <a href="{{ $configs->nextPageUrl() }}" class="btn btn-ghost">
                                Berikutnya
                            </a>
                        @else
                            <span class="btn btn-ghost">
                                Berikutnya
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-blob">🔍</div>
                <div class="empty-title">Endpoint tidak ditemukan</div>
                <div class="empty-sub">
                    Coba ubah filter atau reset pencarian API Configs.
                </div>
            </div>
        @endif
    </div>
@endsection