@extends('admin.layout')

@section('title', 'API Logs — Wargaku Bridging API')
@section('page_title', 'API Logs')

@section('content')
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">API logs</div>
            <span class="panel-tag">{{ $logs->total() }} event</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service</th>
                    <th>Local endpoint</th>
                    <th>Target endpoint</th>
                    <th>Method</th>
                    <th>Status code</th>
                    <th>Success</th>
                    <th>Created at</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="no-col">{{ $log->id }}</td>
                        <td class="svc-name">{{ $log->service_name }}</td>
                        <td class="mono">{{ $log->local_endpoint }}</td>
                        <td class="mono">{{ $log->target_endpoint }}</td>
                        <td>
                            <span class="badge badge-method">{{ $log->method }}</span>
                        </td>
                        <td class="mono">{{ $log->status_code }}</td>
                        <td>
                            @if($log->is_success)
                                <span class="badge badge-ok"><span class="dot-inline"></span>Success</span>
                            @else
                                <span class="badge badge-fail"><span class="dot-inline"></span>Failed</span>
                            @endif
                        </td>
                        <td class="mono">{{ $log->created_at }}</td>
                        <td>
                            <a class="btn btn-ghost" href="{{ route('admin.api-logs.show', $log->id) }}">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="mono">Belum ada log request.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $logs->links() }}
        </div>
    </div>
@endsection