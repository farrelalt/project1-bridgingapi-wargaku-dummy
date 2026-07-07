@extends('admin.layout')

@section('title', 'API Logs — Wargaku Bridging API')
@section('page_title', 'API Logs')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Filter API Logs</h3>
                <div class="panel-sub">
                    Cari log berdasarkan service, method, status, tanggal, atau endpoint
                </div>
            </div>

            <div class="btn-row">
                <a href="{{ route('admin.api-logs.index') }}" class="btn btn-ghost">
                    Reset Filter
                </a>

                <form
                    method="POST"
                    action="{{ route('admin.api-logs.clear-failed') }}"
                    onsubmit="return openWargakuDeleteModal(this, 'failed')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-ghost">
                        Clear Failed Logs
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('admin.api-logs.clear-all') }}"
                    onsubmit="return openWargakuDeleteModal(this, 'all')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-primary">
                        Clear All Logs
                    </button>
                </form>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.api-logs.index') }}">
            <div class="filter-grid">
                <div class="field">
                    <label>Service</label>
                    <select name="service_name">
                        <option value="">Semua service</option>

                        @foreach ($serviceNames ?? [] as $serviceName)
                            <option
                                value="{{ $serviceName }}"
                                {{ request('service_name') == $serviceName ? 'selected' : '' }}
                            >
                                {{ $serviceName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Method</label>
                    <select name="method">
                        <option value="">Semua method</option>

                        <option value="GET" {{ request('method') == 'GET' ? 'selected' : '' }}>
                            GET
                        </option>

                        <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>
                            POST
                        </option>

                        <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>
                            PUT
                        </option>

                        <option value="PATCH" {{ request('method') == 'PATCH' ? 'selected' : '' }}>
                            PATCH
                        </option>

                        <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>
                            DELETE
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Status Code</label>
                    <input
                        type="number"
                        name="status_code"
                        placeholder="Contoh: 503"
                        value="{{ request('status_code') }}"
                    >
                </div>

                <div class="field">
                    <label>Status Request</label>
                    <select name="is_success">
                        <option value="">Semua status</option>

                        <option value="1" {{ request('is_success') === '1' ? 'selected' : '' }}>
                            Success
                        </option>

                        <option value="0" {{ request('is_success') === '0' ? 'selected' : '' }}>
                            Failed
                        </option>
                    </select>
                </div>

                <div class="field">
                    <label>Tanggal Awal</label>
                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                    >
                </div>

                <div class="field">
                    <label>Tanggal Akhir</label>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                    >
                </div>

                <div class="field" style="grid-column: span 2;">
                    <label>Keyword</label>
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Cari endpoint, service, atau error..."
                        value="{{ request('keyword') }}"
                    >
                </div>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.api-logs.index') }}" class="btn btn-ghost">
                    Bersihkan
                </a>
            </div>
        </form>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Daftar API Logs</h3>
                <div class="panel-sub">
                    Total data ditemukan: {{ $logs->total() }}
                </div>
            </div>

            <span class="badge">
                Page {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
            </span>
        </div>

        @if ($logs->count() > 0)
            <div class="rows">
                <div class="row-head log-head">
                    <div>ID</div>
                    <div>Service</div>
                    <div>Endpoint Lokal</div>
                    <div>Target Endpoint</div>
                    <div>Method</div>
                    <div>Status Code</div>
                    <div>Success</div>
                    <div>Created At</div>
                    <div>Aksi</div>
                </div>

                @foreach ($logs as $log)
                    @php
                        $methodClass = strtolower($log->method ?? '');
                    @endphp

                    <div class="row-card log-card">
                        <div>
                            {{ $log->id }}
                        </div>

                        <div>
                            <b>{{ $log->service_name ?? '-' }}</b>
                        </div>

                        <div class="mono">
                            {{ $log->local_endpoint ?? '-' }}
                        </div>

                        <div class="mono">
                            {{ $log->target_endpoint ?? '-' }}
                        </div>

                        <div>
                            <span class="method-pill {{ $methodClass }}">
                                {{ $log->method ?? '-' }}
                            </span>
                        </div>

                        <div class="mono">
                            {{ $log->status_code ?? '-' }}
                        </div>

                        <div>
                            @if ($log->is_success)
                                <span class="status-pill-table success">
                                    <span class="d"></span>
                                    Success
                                </span>
                            @else
                                <span class="status-pill-table fail">
                                    <span class="d"></span>
                                    Failed
                                </span>
                            @endif
                        </div>

                        <div class="mono">
                            {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '-' }}
                        </div>

                        <div>
                            <a href="{{ route('admin.api-logs.show', $log->id) }}" class="edit-btn">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($logs->hasPages())
                <div class="pagination-simple">
                    <div>
                        @if ($logs->onFirstPage())
                            <span class="btn btn-ghost">
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}" class="btn btn-ghost">
                                Sebelumnya
                            </a>
                        @endif
                    </div>

                    <span class="badge">
                        Page {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
                    </span>

                    <div>
                        @if ($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}" class="btn btn-ghost">
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
                <div class="empty-title">Belum ada log yang cocok</div>
                <div class="empty-sub">
                    Coba ubah filter atau reset pencarian kamu.
                </div>
            </div>
        @endif
    </div>
@endsection