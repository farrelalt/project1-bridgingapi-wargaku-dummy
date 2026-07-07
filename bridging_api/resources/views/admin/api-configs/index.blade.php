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
                    {{ $configs->count() }} endpoint
                </span>

                <a
                    href="{{ route('admin.api-configs.create') }}"
                    class="api-config-head-btn api-config-add-btn"
                >
                    Tambah Endpoint
                </a>
            </div>
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
                    @endphp

                    <div class="row-card config-card">
                        <div>
                            {{ $loop->iteration }}
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
                            <a
                                href="{{ route('admin.api-configs.edit', $config->id) }}"
                                class="edit-btn"
                            >
                                Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-blob">📡</div>
                <div class="empty-title">Belum ada API Config</div>
                <div class="empty-sub">
                    Tambahkan endpoint baru untuk mulai melakukan mapping ke Media Center.
                </div>
            </div>
        @endif
    </div>
@endsection