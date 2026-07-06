@extends('admin.layout')

@section('title', 'API Configs — Wargaku Bridging API')
@section('page_title', 'API Configs')

@section('content')
    @if(session('success'))
        <div class="alert">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">API configs</div>
            <span class="panel-tag">{{ $configs->count() }} endpoint</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Service</th>
                    <th>Local endpoint</th>
                    <th>Target endpoint</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Restricted</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($configs as $config)
                    <tr>
                        <td class="no-col">{{ $loop->iteration }}</td>
                        <td class="svc-name">{{ $config->service_name }}</td>
                        <td class="mono">{{ $config->local_endpoint }}</td>
                        <td class="mono">{{ $config->target_endpoint }}</td>
                        <td>
                            <span class="badge badge-method">{{ $config->method }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($config->status) {
                                    'active' => 'badge-active',
                                    'maintenance' => 'badge-maintenance',
                                    default => 'badge-inactive',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                <span class="dot-inline"></span>{{ $config->status }}
                            </span>
                        </td>
                        <td class="{{ $config->is_restricted ? 'restrict-yes' : 'restrict-no' }}">
                            {{ $config->is_restricted ? 'Yes' : 'No' }}
                        </td>
                        <td>
                            <a class="btn" href="{{ route('admin.api-configs.edit', $config->id) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection