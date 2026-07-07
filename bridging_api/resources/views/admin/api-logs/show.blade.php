@extends('admin.layout')

@section('title', 'Detail API Log — Wargaku Bridging API')
@section('page_title', 'Detail API Log')

@section('content')
    @php
        $methodClass = strtolower($log->method);

        $requestPayload = $log->request_payload ?? [];
        $responsePayload = $log->response_payload ?? [];

        $formattedRequestPayload = json_encode(
            $requestPayload,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        $formattedResponsePayload = json_encode(
            $responsePayload,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    @endphp

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Informasi Log</h3>
                <div class="panel-sub">
                    Detail request dan response dari aktivitas bridging API.
                </div>
            </div>

            <a href="{{ route('admin.api-logs.index') }}" class="btn btn-ghost">
                Kembali
            </a>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <div class="detail-label">Service</div>
                <div class="detail-value">{{ $log->service_name ?? '-' }}</div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Method</div>
                <div class="detail-value">
                    <span class="method-pill {{ $methodClass }}">
                        {{ $log->method }}
                    </span>
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Status Code</div>
                <div class="detail-value mono">
                    {{ $log->status_code }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Status Request</div>
                <div class="detail-value">
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
            </div>

            <div class="detail-card detail-card-wide">
                <div class="detail-label">Endpoint Lokal</div>
                <div class="detail-value mono">
                    {{ $log->local_endpoint ?? '-' }}
                </div>
            </div>

            <div class="detail-card detail-card-wide">
                <div class="detail-label">Target Endpoint</div>
                <div class="detail-value mono">
                    {{ $log->target_endpoint ?? '-' }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Dibuat Pada</div>
                <div class="detail-value mono">
                    {{ $log->created_at }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-label">Diperbarui Pada</div>
                <div class="detail-value mono">
                    {{ $log->updated_at }}
                </div>
            </div>
        </div>
    </div>

    @if (!$log->is_success && $log->error_message)
        <div class="panel">
            <div class="panel-head">
                <div>
                    <h3 class="panel-title">Error Message</h3>
                    <div class="panel-sub">
                        Pesan error yang tercatat saat request diproses.
                    </div>
                </div>

                <span class="badge">Failed {{ $log->status_code }}</span>
            </div>

            <div class="error-box">
                {{ $log->error_message }}
            </div>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Request Payload</h3>
                <div class="panel-sub">
                    Data yang dikirim dari client ke Bridging API.
                </div>
            </div>

            <button type="button" class="btn btn-ghost" onclick="copyToClipboard('request-payload')">
                Copy JSON
            </button>
        </div>

        <pre class="json-box" id="request-payload"><code>{{ $formattedRequestPayload ?: '{}' }}</code></pre>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h3 class="panel-title">Response Payload</h3>
                <div class="panel-sub">
                    Response yang dikembalikan dari Media Center atau dari Bridging API.
                </div>
            </div>

            <button type="button" class="btn btn-ghost" onclick="copyToClipboard('response-payload')">
                Copy JSON
            </button>
        </div>

        <pre class="json-box" id="response-payload"><code>{{ $formattedResponsePayload ?: '{}' }}</code></pre>
    </div>
@endsection

@push('scripts')
    <script>
        async function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);

            if (!element) {
                return;
            }

            const text = element.innerText;

            try {
                await navigator.clipboard.writeText(text);
                alert('JSON berhasil disalin.');
            } catch (error) {
                alert('Gagal menyalin JSON.');
            }
        }
    </script>
@endpush