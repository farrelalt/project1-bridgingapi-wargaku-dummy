@extends('admin.layout')

@section('title', 'Dashboard — Wargaku Bridging API')
@section('page_title', 'Dashboard')

@section('content')
    <div class="stat-grid">
        <div class="stat-card pink">
            <div class="stat-label">Total Endpoint</div>
            <div class="stat-value">{{ $totalConfigs }}</div>
        </div>

        <div class="stat-card mint">
            <div class="stat-label">Endpoint Aktif</div>
            <div class="stat-value">{{ $activeConfigs }}</div>
        </div>

        <div class="stat-card amber">
            <div class="stat-label">Total Request</div>
            <div class="stat-value">{{ $totalLogs }}</div>
        </div>

        <div class="stat-card mint">
            <div class="stat-label">Request Sukses</div>
            <div class="stat-value">{{ $successLogs }}</div>
        </div>

        <div class="stat-card pink">
            <div class="stat-label">Request Gagal</div>
            <div class="stat-value">{{ $failedLogs }}</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Media center target</h3>
                <div class="panel-sub">Endpoint utama yang sedang di-bridging</div>
            </div>
        </div>

        <div class="health-row">
            <div class="health-input">{{ $mediaCenterBaseUrl }}</div>

            <button type="button" class="btn btn-primary" id="health-btn" onclick="runHealthCheck()">
                Health check
            </button>
        </div>

        <div id="health-result"></div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Log terbaru</h3>
                <div class="panel-sub">Aktivitas request 5 event terakhir</div>
            </div>

            <span class="badge">5 event terbaru</span>
        </div>

        @if ($latestLogs->count() > 0)
            <div class="rows">
                <div class="row-head latest-head">
                    <div>Service</div>
                    <div>Endpoint Lokal</div>
                    <div>Target</div>
                    <div>Method</div>
                    <div>Status</div>
                    <div>Waktu</div>
                </div>

                @foreach ($latestLogs as $log)
                    @php
                        $methodClass = strtolower($log->method);
                    @endphp

                    <div class="row-card latest-card">
                        <div><b>{{ $log->service_name ?? '-' }}</b></div>
                        <div class="mono">{{ $log->local_endpoint ?? '-' }}</div>
                        <div class="mono">{{ $log->target_endpoint ?? '-' }}</div>

                        <div>
                            <span class="method-pill {{ $methodClass }}">
                                {{ $log->method }}
                            </span>
                        </div>

                        <div>
                            @if ($log->is_success)
                                <span class="status-pill-table success">
                                    <span class="d"></span>
                                    Success {{ $log->status_code }}
                                </span>
                            @else
                                <span class="status-pill-table fail">
                                    <span class="d"></span>
                                    Failed {{ $log->status_code }}
                                </span>
                            @endif
                        </div>

                        <div class="mono">
                            {{ $log->created_at }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="log-empty-row">
                Belum ada log request.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        async function runHealthCheck() {
            const button = document.getElementById('health-btn');
            const resultBox = document.getElementById('health-result');

            button.disabled = true;
            button.textContent = 'Checking...';

            resultBox.innerHTML = `
                <div class="health-wrap">
                    <div class="health-loading">
                        <span class="spin"></span>
                        Menjalankan health check...
                    </div>
                </div>
            `;

            try {
                const response = await fetch('{{ url('/api/v2/health') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                renderHealthResult(result);
            } catch (error) {
                resultBox.innerHTML = `
                    <div class="health-wrap">
                        <div class="health-summary">
                            <span class="health-ring warn"></span>
                            <span>
                                <b>Health check gagal</b> — Browser tidak bisa memanggil endpoint health.
                            </span>
                        </div>

                        <div class="health-error">
                            ${escapeHtml(error.message)}
                        </div>
                    </div>
                `;
            } finally {
                button.disabled = false;
                button.textContent = 'Health check';
            }
        }

        function renderHealthResult(result) {
            const resultBox = document.getElementById('health-result');
            const data = result.data || {};

            const bridgingApi = data.bridging_api || {};
            const database = data.database || {};
            const mediaCenter = data.media_center || {};

            const bridgingOk = bridgingApi.status === 'running';
            const databaseOk = database.connected === true;
            const mediaCenterOk = mediaCenter.reachable === true;

            const allOk = bridgingOk && databaseOk && mediaCenterOk;

            resultBox.innerHTML = `
                <div class="health-wrap">
                    <div class="health-summary">
                        <span class="health-ring ${allOk ? 'ok' : 'warn'}"></span>
                        <span>
                            <b>${result.success ? 'Health check selesai' : 'Health check gagal'}</b>
                            — ${escapeHtml(result.message || '-')}
                        </span>
                    </div>

                    <div class="health-grid">
                        <div class="health-card ${bridgingOk ? 'ok' : 'fail'}">
                            <div class="health-card-top">
                                <span class="health-card-name">Bridging API</span>
                                <span class="health-card-status">${escapeHtml(bridgingApi.status || 'unknown')}</span>
                            </div>

                            <div class="health-card-row">
                                <span>App name</span>
                                <span class="v">${escapeHtml(bridgingApi.app_name || '-')}</span>
                            </div>

                            <div class="health-card-row">
                                <span>App URL</span>
                                <span class="v">${escapeHtml(bridgingApi.app_url || '-')}</span>
                            </div>
                        </div>

                        <div class="health-card ${databaseOk ? 'ok' : 'fail'}">
                            <div class="health-card-top">
                                <span class="health-card-name">Database</span>
                                <span class="health-card-status">${databaseOk ? 'connected' : 'disconnected'}</span>
                            </div>

                            <div class="health-card-row">
                                <span>Driver</span>
                                <span class="v">${escapeHtml(database.connection || '-')}</span>
                            </div>

                            <div class="health-card-row">
                                <span>Pesan</span>
                                <span class="v">${escapeHtml(database.message || '-')}</span>
                            </div>

                            ${database.error ? `
                                <div class="health-error">
                                    ${escapeHtml(database.error)}
                                </div>
                            ` : ''}
                        </div>

                        <div class="health-card ${mediaCenterOk ? 'ok' : 'fail'}">
                            <div class="health-card-top">
                                <span class="health-card-name">Media Center</span>
                                <span class="health-card-status">${mediaCenterOk ? 'reachable' : 'unreachable'}</span>
                            </div>

                            <div class="health-card-row">
                                <span>Base URL</span>
                                <span class="v">${escapeHtml(mediaCenter.base_url || '-')}</span>
                            </div>

                            <div class="health-card-row">
                                <span>HTTP status</span>
                                <span class="v">${mediaCenter.http_status ?? 'null'}</span>
                            </div>

                            <div class="health-card-row">
                                <span>Pesan</span>
                                <span class="v">${escapeHtml(mediaCenter.message || '-')}</span>
                            </div>

                            ${mediaCenter.error ? `
                                <div class="health-error">
                                    ${escapeHtml(mediaCenter.error)}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            `;
        }

        function escapeHtml(value) {
            return String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }
    </script>
@endpush