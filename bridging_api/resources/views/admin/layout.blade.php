<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Wargaku Bridging API — Admin')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --red-50:#FDF3F3;
            --red-100:#FBE2E2;
            --red-200:#F3B7B7;
            --red-500:#C81E2C;
            --red-600:#A8161F;
            --red-700:#7E1017;
            --ink:#1A1715;
            --ink-soft:#6B6560;
            --ink-faint:#A39D97;
            --line:#ECE7E3;
            --paper:#FFFFFF;
            --paper-off:#FBF9F7;
            --green-50:#EAF5EB;
            --green-200:#CFE8D1;
            --green-700:#2E7D32;
            --yellow-50:#FEF3C7;
            --yellow-700:#92400E;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--paper-off);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
        }

        .shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            flex-shrink: 0;
            background: var(--paper);
            border-right: 1px solid var(--line);
            display: flex;
            flex-direction: column;
            padding: 28px 0;
        }

        .brand {
            padding: 0 24px 28px 24px;
            border-bottom: 1px solid var(--line);
            margin-bottom: 20px;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-glyph {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            background: var(--red-500);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 500;
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 15.5px;
            letter-spacing: -0.01em;
        }

        .brand-sub {
            font-size: 11px;
            color: var(--ink-faint);
            margin-top: 2px;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 0 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--ink-soft);
            border-left: 3px solid transparent;
            transition: background .15s;
        }

        .nav-item:hover {
            background: var(--paper-off);
        }

        .nav-item.active {
            background: var(--red-50);
            color: var(--red-700);
            border-left: 3px solid var(--red-500);
        }

        .nav-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: .55;
        }

        .nav-item.active .nav-dot {
            opacity: 1;
        }

        .sidebar-foot {
            margin-top: auto;
            padding: 16px 24px 0 24px;
            border-top: 1px solid var(--line);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: var(--ink-soft);
            margin-top: 14px;
            word-break: break-all;
        }

        .status-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red-500);
        }

        .main {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            background: var(--paper);
            border-bottom: 1px solid var(--line);
            padding: 20px 36px;
            display: flex;
            align-items: baseline;
            justify-content: space-between;
        }

        .page-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 22px;
            letter-spacing: -0.01em;
        }

        .page-meta {
            font-size: 12.5px;
            color: var(--ink-faint);
            font-family: 'JetBrains Mono', monospace;
        }

        .content {
            padding: 32px 36px 48px 36px;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 18px 18px 16px 18px;
            position: relative;
            overflow: hidden;
        }

        .stat-card.accent {
            border-color: var(--red-200);
            background: var(--red-50);
        }

        .stat-card.accent-green {
            border-color: var(--green-200);
            background: var(--green-50);
        }

        .stat-label {
            font-size: 11.5px;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .stat-value {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 30px;
            margin-top: 8px;
            color: var(--ink);
        }

        .stat-card.accent .stat-value {
            color: var(--red-600);
        }

        .stat-card.accent-green .stat-value {
            color: var(--green-700);
        }

        .stat-bar {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 100%;
            background: var(--line);
        }

        .stat-card.accent .stat-bar {
            background: var(--red-500);
        }

        .stat-card.accent-green .stat-bar {
            background: var(--green-700);
        }

        .panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 12px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .panel-head {
            padding: 16px 20px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-title {
            font-size: 14.5px;
            font-weight: 600;
        }

        .panel-tag {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11.5px;
            color: var(--red-600);
            background: var(--red-50);
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid var(--red-100);
        }

        .target-row {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .target-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 13.5px;
            color: var(--ink);
            background: var(--paper-off);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 8px 12px;
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead th {
            text-align: left;
            padding: 10px 20px;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink-faint);
            text-transform: uppercase;
            letter-spacing: .04em;
            border-bottom: 1px solid var(--line);
            background: var(--paper-off);
        }

        tbody td {
            padding: 12px 20px;
            border-bottom: 1px solid var(--line);
            color: var(--ink);
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: var(--paper-off);
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--ink-soft);
        }

        .svc-name {
            font-weight: 500;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
        }

        .badge-fail {
            background: var(--red-50);
            color: var(--red-600);
            border: 1px solid var(--red-100);
        }

        .badge-ok {
            background: var(--green-50);
            color: var(--green-700);
            border: 1px solid var(--green-200);
        }

        .badge-active {
            background: var(--green-50);
            color: var(--green-700);
            border: 1px solid var(--green-200);
        }

        .badge-inactive {
            background: var(--red-50);
            color: var(--red-600);
            border: 1px solid var(--red-100);
        }

        .badge-maintenance {
            background: var(--yellow-50);
            color: var(--yellow-700);
            border: 1px solid var(--yellow-50);
        }

        .badge-method {
            background: var(--paper-off);
            color: var(--ink-soft);
            border: 1px solid var(--line);
        }

        .dot-inline {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .btn {
            font-family: 'Inter', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: #fff;
            background: var(--red-500);
            border: none;
            padding: 7px 14px;
            border-radius: 7px;
            cursor: pointer;
            display: inline-block;
        }

        .btn:hover {
            background: var(--red-600);
        }

        .btn-ghost {
            background: transparent;
            color: var(--red-600);
            border: 1px solid var(--red-200);
        }

        .btn-ghost:hover {
            background: var(--red-50);
        }

        .no-col {
            color: var(--ink-faint);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
        }

        .restrict-yes {
            color: var(--red-600);
            font-weight: 600;
        }

        .restrict-no {
            color: var(--ink-faint);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
            background: var(--green-50);
            color: var(--green-700);
            border: 1px solid var(--green-200);
            font-size: 13px;
            font-weight: 600;
        }

        .form-card {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 22px;
            max-width: 760px;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 6px;
            display: block;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            margin-bottom: 16px;
            background: var(--paper-off);
            font-family: 'Inter', sans-serif;
            color: var(--ink);
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        input:disabled {
            color: var(--ink-faint);
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            color: var(--ink-soft);
            font-size: 13px;
        }

        .checkbox-row input {
            width: auto;
            margin: 0;
        }

        pre {
            background: #111827;
            color: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            line-height: 1.6;
        }

        .pagination {
            padding: 16px 20px;
            font-size: 13px;
        }

        @media (max-width: 1200px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .sidebar {
                width: 210px;
            }

            table {
                min-width: 900px;
            }

            .panel {
                overflow-x: auto;
            }
        }
        /* Health check result */
        .health-wrap {
            border-top: 1px solid var(--line);
            padding: 18px 20px 20px 20px;
        }

        .health-summary {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .health-ring {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .health-ring.ok {
            background: var(--green-700);
        }

        .health-ring.warn {
            background: var(--red-500);
        }

        .health-summary-text {
            font-size: 13px;
            color: var(--ink-soft);
        }

        .health-summary-text b {
            color: var(--ink);
            font-weight: 600;
        }

        .health-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .health-card {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 14px 16px;
            background: var(--paper-off);
        }

        .health-card.ok {
            border-color: var(--green-200);
            background: var(--green-50);
        }

        .health-card.fail {
            border-color: var(--red-100);
            background: var(--red-50);
        }

        .health-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .health-card-name {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink);
        }

        .health-card-status {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10.5px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 5px;
        }

        .health-card.ok .health-card-status {
            background: #DBEFDC;
            color: var(--green-700);
        }

        .health-card.fail .health-card-status {
            background: var(--red-100);
            color: var(--red-600);
        }

        .health-card-row {
            font-size: 12px;
            color: var(--ink-soft);
            display: flex;
            justify-content: space-between;
            gap: 8px;
            padding: 3px 0;
        }

        .health-card-row .v {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--ink);
            text-align: right;
            word-break: break-all;
        }

        .health-error {
            margin-top: 8px;
            padding: 8px 10px;
            background: var(--paper);
            border: 1px solid var(--red-100);
            border-radius: 7px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--red-700);
            line-height: 1.5;
            word-break: break-word;
        }

        .health-loading {
            font-size: 12.5px;
            color: var(--ink-faint);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .health-loading .spin {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid var(--line);
            border-top-color: var(--red-500);
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 1200px) {
            .health-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
<div class="shell">
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-mark">
                <div class="brand-glyph">&lt;/&gt;</div>
                <div>
                    <div class="brand-name">Wargaku</div>
                    <div class="brand-sub">Bridging API</div>
                </div>
            </div>
        </div>

        <nav class="nav">
            <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <span class="nav-dot"></span>Dashboard
            </a>

            <a class="nav-item {{ request()->routeIs('admin.api-configs.*') ? 'active' : '' }}" href="{{ route('admin.api-configs.index') }}">
                <span class="nav-dot"></span>API Configs
            </a>

            <a class="nav-item {{ request()->routeIs('admin.api-logs.*') ? 'active' : '' }}" href="{{ route('admin.api-logs.index') }}">
                <span class="nav-dot"></span>API Logs
            </a>
        </nav>

        <div class="sidebar-foot">
            <div class="status-pill">
                <span class="dot"></span>
                Target: {{ str_replace('http://', '', env('MEDIA_CENTER_BASE_URL', '127.0.0.1:8002/api')) }}
            </div>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div>
                <div class="page-title">@yield('page_title', 'Dashboard')</div>
            </div>
            <div class="page-meta">{{ now()->format('d/m/Y · H:i') }}</div>
        </div>

        <div class="content">
            @yield('content')
        </div>
    </main>
</div>
@stack('scripts')
</body>
</html>