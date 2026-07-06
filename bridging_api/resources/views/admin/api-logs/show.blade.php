@extends('admin.layout')

@section('title', 'Detail API Log — Wargaku Bridging API')
@section('page_title', 'Detail API Log #' . $log->id)

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Informasi request</div>
            @if($log->is_success)
                <span class="badge badge-ok"><span class="dot-inline"></span>Success {{ $log->status_code }}</span>
            @else
                <span class="badge badge-fail"><span class="dot-inline"></span>Failed {{ $log->status_code }}</span>
            @endif
        </div>

        <table>
            <tbody>
                <tr>
                    <td class="svc-name">Service</td>
                    <td>{{ $log->service_name }}</td>
                </tr>
                <tr>
                    <td class="svc-name">Local Endpoint</td>
                    <td class="mono">{{ $log->local_endpoint }}</td>
                </tr>
                <tr>
                    <td class="svc-name">Target Endpoint</td>
                    <td class="mono">{{ $log->target_endpoint }}</td>
                </tr>
                <tr>
                    <td class="svc-name">Method</td>
                    <td><span class="badge badge-method">{{ $log->method }}</span></td>
                </tr>
                <tr>
                    <td class="svc-name">Error Message</td>
                    <td>{{ $log->error_message ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="svc-name">Created At</td>
                    <td class="mono">{{ $log->created_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Request payload</div>
        </div>
        <div style="padding: 20px;">
            <pre>{{ json_encode($log->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">Response payload</div>
        </div>
        <div style="padding: 20px;">
            <pre>{{ json_encode($log->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
    </div>

    <a class="btn btn-ghost" href="{{ route('admin.api-logs.index') }}">Kembali</a>
@endsection