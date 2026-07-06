@extends('admin.layout')

@section('title', 'Dashboard — Wargaku Bridging API')
@section('page_title', 'Dashboard')

@section('content')
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-label">Total endpoint</div>
            <div class="stat-value">{{ $totalConfigs }}</div>
            <div class="stat-bar"></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Endpoint aktif</div>
            <div class="stat-value">{{ $activeConfigs }}</div>
            <div class="stat-bar"></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Total request</div>
            <div class="stat-value">{{ $totalLogs }}</div>
            <div class="stat-bar"></div>
        </div>

        <div class="stat-card {{ $successLogs > 0 ? 'accent-green' : '' }}">
            <div class="stat-label">Request sukses</div>
            <div class="stat-value">{{ $successLogs }}</div>
            <div class="stat-bar"></div>
        </div>

        <div class="stat-card {{ $failedLogs > 0 ? 'accent' : '' }}">
            <div class="stat-label">Request gagal</div>
            <div class="stat-value">{{ $failedLogs }}</div>
            <div class="stat-bar"></div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Media center target</div>
        </div>

        <div class="target-row">
            <div class="target-value">{{ $mediaCenterBaseUrl }}</div>

            <button type="button" class="btn btn-ghost" id="health-btn" onclick="runHealthCheck()">
                Health check
            </button>
        </div>

        <div id="health-result"></div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Log terbaru</div>
            <span class="panel-tag">5 event terbaru</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Endpoint lokal</th>
                    <th>Target</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($latestLogs as $log)
                    <tr>
                        <td class="svc-name">
                            {{ $log->service_name }}
                        </td>

                        <td class="mono">
                            {{ $log->local_endpoint }}
                        </td>

                        <td class="mono">
                            {{ $log->target_endpoint }}
                        </td>

                        <td>
                            <span class="badge badge-method">
                                {{ $log->method }}
                            </span>
                        </td>

                        <td>
                            @if ($log->is_success)
                                <span class="badge badge-ok">
                                    <span class="dot-inline"></span>
                                    Success {{ $log->status_code }}
                                </span>
                            @else
                                <span class="badge badge-fail">
                                    <span class="dot-inline"></span>
                                    Failed {{ $log->status_code }}
                                </span>
                            @endif
                        </td>

                        <td class="mono">
                            {{ $log->created_at }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="mono">
                            Belum ada log request.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
                            <span class="health-summary-text">
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
                        <span class="health-summary-text">
                            <b>${result.success ? 'Health check selesai' : 'Health check gagal'}</b>
                            — ${escapeHtml(result.message || '-')}
                        </span>
                    </div>

                    <div class="health-grid">
                        <div class="health-card ${bridgingOk ? 'ok' : 'fail'}">
                            <div class="health-card-top">
                                <span class="health-card-name">Bridging API</span>
                                <span class="health-card-status">
                                    ${escapeHtml(bridgingApi.status || 'unknown')}
                                </span>
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
                                <span class="health-card-status">
                                    ${databaseOk ? 'connected' : 'disconnected'}
                                </span>
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
                                <span class="health-card-status">
                                    ${mediaCenterOk ? 'reachable' : 'unreachable'}
                                </span>
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