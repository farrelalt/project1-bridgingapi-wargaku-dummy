@php
    $targetBaseUrl = env('MEDIA_CENTER_BASE_URL', 'http://127.0.0.1:8002/api');
    $targetLabel = preg_replace('#^https?://#', '', $targetBaseUrl);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Wargaku Bridging API')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        :root {
            --maroon-900: #4A1420;
            --maroon-700: #6E1E2F;
            --maroon-500: #8C2A3F;
            --coral: #FF5D6C;
            --coral-deep: #E23A4C;
            --coral-soft: #FFE1E4;
            --cream: #FBF3E9;
            --cream-deep: #F3E7D8;
            --white: #FFFFFF;
            --mint: #22C08E;
            --mint-soft: #DDF6EC;
            --amber: #FFB446;
            --amber-soft: #FFF1DA;
            --ink: #2B1420;
            --ink-soft: #8B7480;
            --radius-lg: 28px;
            --radius-md: 20px;
            --radius-sm: 14px;
            --shadow-soft: 0 12px 30px -10px rgba(74, 20, 32, 0.18);
            --shadow-pop: 0 18px 40px -12px rgba(255, 93, 108, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 100% 0%, #FFE3D6 0%, transparent 45%),
                radial-gradient(circle at 0% 100%, #FFD9E0 0%, transparent 40%),
                var(--cream);
            color: var(--ink);
        }

        h1,
        h2,
        h3,
        .display {
            font-family: 'Baloo 2', sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        ::selection {
            background: var(--coral-soft);
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        /* ================= SIDEBAR ================= */

        .sidebar {
            width: 260px;
            flex-shrink: 0;
            background: linear-gradient(
                165deg,
                var(--maroon-900) 0%,
                var(--maroon-700) 60%,
                var(--maroon-500) 100%
            );
            border-radius: 0 40px 40px 0;
            padding: 28px 20px;
            display: flex;
            flex-direction: column;
            gap: 28px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: hidden;
            color: var(--cream);
        }

        .sidebar::before {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(255, 93, 108, 0.45), transparent 70%);
            top: -80px;
            right: -80px;
            border-radius: 50%;
            filter: blur(10px);
        }

        .brand {
            position: relative;
            z-index: 1;
            color: inherit;
            text-decoration: none;
        }

        .brand-with-logo {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .brand-logo-card {
            width: 100%;
            background: var(--white);
            border-radius: 20px;
            padding: 12px;
            box-shadow: var(--shadow-soft);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-logo-image {
            width: 100%;
            max-width: 120px;
            height: auto;
            display: block;
            object-fit: contain;
        }

        .brand-app-name {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, 0.82);
            text-transform: uppercase;
            padding-left: 4px;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            z-index: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 16px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 14.5px;
            color: rgba(255, 255, 255, 0.75);
            cursor: pointer;
            transition: all .25s ease;
            border: 1px solid transparent;
        }

        .nav-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            opacity: .6;
            flex-shrink: 0;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .nav-item.active {
            background: linear-gradient(135deg, var(--coral), #FF7B6E);
            color: #fff;
            box-shadow: var(--shadow-pop);
        }

        .nav-item.active .dot {
            background: #fff;
            opacity: 1;
        }

        .sidebar-footer {
            margin-top: auto;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 16px;
            position: relative;
            z-index: 1;
        }

        .status-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .pulse {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--mint);
            box-shadow: 0 0 0 0 rgba(34, 192, 142, .6);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 192, 142, .6);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(34, 192, 142, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 192, 142, 0);
            }
        }

        .status-target {
            font-size: 11.5px;
            opacity: 0.7;
            font-family: monospace;
            word-break: break-all;
        }

        /* ================= MAIN ================= */

        .main {
            flex: 1;
            padding: 36px 44px;
            min-width: 0;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 4px 0;
        }

        .page-date {
            font-size: 13px;
            color: var(--ink-soft);
            font-weight: 600;
        }

        .avatar {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background: linear-gradient(135deg, #F6E6DF, #FEE8EB);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--maroon-900);
            box-shadow: var(--shadow-soft);
            font-family: 'Baloo 2', sans-serif;
        }

        /* ================= FLASH MESSAGE ================= */

        .flash-message {
            margin-bottom: 20px;
            padding: 14px 18px;
            border-radius: 18px;
            font-size: 13.5px;
            font-weight: 700;
            box-shadow: var(--shadow-soft);
        }

        .flash-message.success {
            background: var(--mint-soft);
            color: #128A64;
        }

        .flash-message.error {
            background: var(--coral-soft);
            color: var(--maroon-900);
        }

        /* ================= STAT CARDS ================= */

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 26px;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: var(--shadow-soft);
            position: relative;
            overflow: hidden;
            transition: transform .25s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-card::after {
            content: "";
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            top: -30px;
            right: -30px;
            opacity: .15;
        }

        .stat-card.pink::after {
            background: var(--coral);
        }

        .stat-card.mint::after {
            background: var(--mint);
        }

        .stat-card.amber::after {
            background: var(--amber);
        }

        .stat-label {
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--ink-soft);
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 30px;
            font-weight: 800;
            font-family: 'Baloo 2', sans-serif;
        }

        /* ================= PANEL ================= */

        .panel {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 28px;
            box-shadow: var(--shadow-soft);
            margin-bottom: 24px;
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
        }

        .panel-sub {
            font-size: 13px;
            color: var(--ink-soft);
            margin-top: 2px;
        }

        .badge {
            background: var(--coral-soft);
            color: var(--coral);
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ================= BUTTONS ================= */

        .btn {
            border: none;
            cursor: pointer;
            padding: 13px 26px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13.5px;
            font-family: inherit;
            transition: transform .2s ease, box-shadow .2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--coral), #FF7B6E);
            color: white;
            box-shadow: var(--shadow-pop);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
        }

        .btn-ghost {
            background: var(--cream);
            color: var(--maroon-700);
            border: 2px solid var(--cream-deep);
        }

        .btn-ghost:hover {
            background: var(--cream-deep);
        }

        .btn-row {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* ================= FORM ================= */

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 18px;
        }

        .field label {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--ink-soft);
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
            text-transform: uppercase;
        }

        .field select,
        .field input,
        .field textarea {
            width: 100%;
            border: 2px solid var(--cream-deep);
            background: var(--cream);
            padding: 12px 16px;
            border-radius: 999px;
            font-size: 13.5px;
            font-family: inherit;
            color: var(--ink);
            outline: none;
            transition: border-color .2s;
        }

        .field textarea {
            border-radius: 18px;
            min-height: 100px;
            resize: vertical;
        }

        .field select:focus,
        .field input:focus,
        .field textarea:focus {
            border-color: var(--coral);
        }

        /* ================= API CONFIG HEADER ACTIONS ================= */

        .api-config-panel-head {
            align-items: center;
        }

        .api-config-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-left: auto;
        }

        .api-config-action-btn {
            width: 150px;
            height: 48px;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.15;
            white-space: normal;
        }

        .api-config-count {
            background: var(--coral-soft);
            color: var(--coral);
        }

        .api-config-add {
            background: linear-gradient(135deg, var(--coral), #FF7B6E);
            color: #fff;
            box-shadow: var(--shadow-pop);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .api-config-add:hover {
            transform: translateY(-2px);
        }

        /* ================= ROW LIST ================= */

        .rows {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .row-head,
        .row-card {
            display: grid;
            align-items: center;
            gap: 12px;
        }

        .row-head {
            padding: 0 18px;
            font-size: 11px;
            font-weight: 700;
            color: var(--ink-soft);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .row-card {
            background: var(--cream);
            border-radius: 18px;
            padding: 14px 18px;
            font-size: 13.5px;
            font-weight: 600;
            transition: background .2s;
        }

        .row-card:hover {
            background: var(--cream-deep);
        }

        .config-head,
        .config-card {
             grid-template-columns: var(--api-config-cols);
        }

        /* ================= API CONFIG PAGE ================= */

        .api-config-panel {
            --api-config-cols: 56px 1fr 1.4fr 1.4fr 110px 110px 120px 180px;
        }
        

        .api-config-panel-head {
            display: grid;
            grid-template-columns: var(--api-config-cols);
            align-items: start;
            gap: 12px;
            margin-bottom: 22px;
        }

        .api-config-head-title {
            grid-column: 1 / 5;
        }

        .api-config-head-controls {
            grid-column: 5 / 9;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .api-config-head-btn {
            width: 150px;
            height: 46px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            line-height: 1.15;
            white-space: normal;
        }

        .api-config-count-btn {
            background: var(--coral-soft);
            color: var(--coral);
        }

        .api-config-add-btn {
            background: linear-gradient(135deg, var(--coral), #FF7B6E);
            color: #fff;
            box-shadow: var(--shadow-pop);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .api-config-add-btn:hover {
            transform: translateY(-2px);
        }

        .config-head > div:nth-child(5),
        .config-head > div:nth-child(6),
        .config-head > div:nth-child(7),
        .config-head > div:nth-child(8),
        .config-card > div:nth-child(5),
        .config-card > div:nth-child(6),
        .config-card > div:nth-child(7),
        .config-card > div:nth-child(8) {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .log-head,
        .log-card {
            grid-template-columns: 60px 1.1fr 1.4fr 1.5fr 90px 110px 100px 150px 100px;
        }

        .latest-head,
        .latest-card {
            grid-template-columns: 1.1fr 1.5fr 1.6fr 90px 130px 150px;
        }

        .mono {
            font-family: monospace;
            font-size: 12.5px;
            color: var(--maroon-700);
            word-break: break-word;
        }

        .method-pill {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            background: var(--maroon-900);
            color: #fff;
            width: fit-content;
            text-transform: uppercase;
        }

        .method-pill.get {
            background: #3D6FE0;
        }

        .method-pill.post {
            background: var(--coral);
        }

        .method-pill.put {
            background: var(--amber);
            color: var(--ink);
        }

        .method-pill.delete {
            background: var(--maroon-900);
        }

        .status-pill-table {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            width: fit-content;
            text-transform: capitalize;
        }

        .status-pill-table .d {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .status-pill-table.success {
            background: var(--mint-soft);
            color: #128A64;
        }

        .status-pill-table.fail {
            background: var(--coral-soft);
            color: var(--coral);
        }

        .status-pill-table.warning {
            background: var(--amber-soft);
            color: #B37400;
        }

        .restricted-yes {
            color: var(--coral);
            font-weight: 800;
        }

        .restricted-no {
            color: var(--ink-soft);
            font-weight: 600;
        }

        .edit-btn {
            background: linear-gradient(135deg, var(--coral), #FF7B6E);
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            width: fit-content;
            box-shadow: 0 8px 18px -6px rgba(255, 93, 108, .5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .config-action-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            flex-wrap: nowrap;
        }

        .delete-btn {
            background: var(--coral-soft);
            color: var(--coral);
            border: none;
            padding: 8px 16px;
            border-radius: 999px;
            font-weight: 800;
            font-size: 12px;
            cursor: pointer;
            width: fit-content;
            font-family: inherit;
            transition: transform .2s ease, background .2s ease;
        }

        .delete-btn:hover {
            background: #FFD1D7;
            transform: translateY(-2px);
        }

        /* ================= HEALTH CHECK ================= */

        .health-row {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .health-input {
            flex: 1;
            border: 2px solid var(--cream-deep);
            background: var(--cream);
            padding: 14px 20px;
            border-radius: 999px;
            font-family: monospace;
            font-size: 13.5px;
            color: var(--maroon-700);
            word-break: break-all;
        }

        .health-wrap {
            margin-top: 18px;
            background: var(--cream);
            border-radius: 20px;
            padding: 18px;
        }

        .health-summary {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            font-size: 13.5px;
            color: var(--ink-soft);
        }

        .health-ring {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .health-ring.ok {
            background: var(--mint);
        }

        .health-ring.warn {
            background: var(--coral);
        }

        .health-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        .health-card {
            border-radius: 18px;
            padding: 16px;
            background: var(--white);
            border: 2px solid var(--cream-deep);
        }

        .health-card.ok {
            border-color: var(--mint-soft);
        }

        .health-card.fail {
            border-color: var(--coral-soft);
        }

        .health-card-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 10px;
        }

        .health-card-name {
            font-weight: 800;
            font-size: 13px;
        }

        .health-card-status {
            font-family: monospace;
            font-size: 11px;
            font-weight: 800;
            padding: 5px 10px;
            border-radius: 999px;
            background: var(--cream);
        }

        .health-card.ok .health-card-status {
            background: var(--mint-soft);
            color: #128A64;
        }

        .health-card.fail .health-card-status {
            background: var(--coral-soft);
            color: var(--coral);
        }

        .health-card-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12.5px;
            color: var(--ink-soft);
            padding: 4px 0;
        }

        .health-card-row .v {
            color: var(--maroon-700);
            font-family: monospace;
            text-align: right;
            word-break: break-word;
        }

        .health-error {
            margin-top: 10px;
            padding: 10px 12px;
            border-radius: 14px;
            background: var(--coral-soft);
            color: var(--maroon-700);
            font-family: monospace;
            font-size: 11.5px;
            line-height: 1.5;
            word-break: break-word;
        }

        .health-loading {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--ink-soft);
            font-size: 13px;
            font-weight: 600;
        }

        .spin {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--cream-deep);
            border-top-color: var(--coral);
            animation: spin .7s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ================= DETAIL API LOG ================= */

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .detail-card {
            background: var(--cream);
            border-radius: 18px;
            padding: 16px 18px;
            border: 2px solid var(--cream-deep);
        }

        .detail-card-wide {
            grid-column: span 2;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 800;
            color: var(--ink-soft);
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--ink);
            word-break: break-word;
        }

        .json-box {
            margin: 0;
            background: var(--maroon-900);
            color: #FFE9EC;
            border-radius: 20px;
            padding: 20px;
            font-family: monospace;
            font-size: 12.5px;
            line-height: 1.7;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .error-box {
            background: var(--coral-soft);
            color: var(--maroon-900);
            border: 2px solid #FFD1D7;
            border-radius: 20px;
            padding: 18px 20px;
            font-family: monospace;
            font-size: 12.5px;
            line-height: 1.7;
            word-break: break-word;
        }

        /* ================= EMPTY STATE ================= */

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--ink-soft);
        }

        .empty-blob {
            width: 90px;
            height: 90px;
            margin: 0 auto 16px;
            border-radius: 32px;
            background: linear-gradient(135deg, var(--coral-soft), var(--amber-soft));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 34px;
        }

        .empty-title {
            font-weight: 800;
            color: var(--ink);
            font-size: 16px;
            margin-bottom: 4px;
        }

        .empty-sub {
            font-size: 13px;
        }

        .log-empty-row {
            background: var(--cream);
            border-radius: 18px;
            padding: 30px;
            text-align: center;
            color: var(--ink-soft);
            font-weight: 600;
            font-size: 13.5px;
        }

        .pagination-simple {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .pagination-simple a,
        .pagination-simple span {
            font-size: 13px;
            font-weight: 700;
        }

        /* ================= WARGAKU CONFIRM MODAL ================= */

        .wargaku-confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(43, 20, 32, 0.55);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 20px;
            animation: confirmFadeIn .2s ease;
        }

        .wargaku-confirm-overlay.show {
            display: flex;
        }

        @keyframes confirmFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .wargaku-confirm-modal {
            background: var(--white);
            width: 100%;
            max-width: 420px;
            border-radius: 32px;
            padding: 32px 30px 26px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 30px 70px -20px rgba(74, 20, 32, 0.45);
            text-align: center;
            animation: confirmPopIn .28s cubic-bezier(.2, .9, .3, 1.3);
        }

        @keyframes confirmPopIn {
            from {
                opacity: 0;
                transform: scale(.85) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .wargaku-confirm-modal::before {
            content: "";
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            top: -110px;
            left: 50%;
            transform: translateX(-50%);
            opacity: .5;
            filter: blur(2px);
        }

        .wargaku-confirm-modal.danger::before {
            background: radial-gradient(circle, var(--coral-soft), transparent 70%);
        }

        .wargaku-confirm-modal.warn::before {
            background: radial-gradient(circle, var(--amber-soft), transparent 70%);
        }

        .wargaku-confirm-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--cream);
            border: none;
            cursor: pointer;
            color: var(--ink-soft);
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: background .15s;
        }

        .wargaku-confirm-close:hover {
            background: var(--cream-deep);
        }

        .wargaku-confirm-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 18px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            position: relative;
            z-index: 1;
            transform: rotate(-4deg);
        }

        .wargaku-confirm-modal.danger .wargaku-confirm-icon {
            background: linear-gradient(135deg, var(--coral-deep), var(--coral));
            box-shadow: var(--shadow-pop);
        }

        .wargaku-confirm-modal.warn .wargaku-confirm-icon {
            background: linear-gradient(135deg, #FF9A3D, var(--amber));
            box-shadow: 0 14px 30px -10px rgba(255, 180, 70, .5);
        }

        .wargaku-confirm-title {
            font-family: 'Baloo 2', sans-serif;
            font-size: 21px;
            font-weight: 800;
            margin: 0 0 10px;
            position: relative;
            z-index: 1;
            color: var(--ink);
        }

        .wargaku-confirm-desc {
            font-size: 14px;
            line-height: 1.55;
            color: var(--ink-soft);
            margin: 0 0 8px;
            position: relative;
            z-index: 1;
        }

        .wargaku-confirm-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--coral-soft);
            color: var(--coral-deep);
            font-size: 11.5px;
            font-weight: 700;
            padding: 7px 14px;
            border-radius: 999px;
            margin: 14px 0 22px;
            position: relative;
            z-index: 1;
        }

        .wargaku-confirm-modal.warn .wargaku-confirm-tag {
            background: var(--amber-soft);
            color: #B36A00;
        }

        .wargaku-confirm-tag .d {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .wargaku-confirm-actions {
            display: flex;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .wargaku-confirm-actions .modal-btn {
            flex: 1;
            border: none;
            cursor: pointer;
            padding: 14px 18px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 14px;
            font-family: inherit;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .wargaku-confirm-actions .modal-btn:active {
            transform: scale(0.96);
        }

        .modal-btn-cancel {
            background: var(--cream);
            color: var(--maroon-700);
            border: 2px solid var(--cream-deep) !important;
        }

        .modal-btn-cancel:hover {
            background: var(--cream-deep);
        }

        .modal-btn-danger {
            background: linear-gradient(135deg, var(--coral-deep), var(--coral));
            color: #fff;
            box-shadow: var(--shadow-pop);
        }

        .modal-btn-danger:hover {
            transform: translateY(-2px);
        }

        .modal-btn-warn {
            background: linear-gradient(135deg, #FF9A3D, var(--amber));
            color: #fff;
            box-shadow: 0 14px 30px -10px rgba(255, 180, 70, .5);
        }

        .modal-btn-warn:hover {
            transform: translateY(-2px);
        }

        /* ================= RESPONSIVE ================= */

        @media (max-width: 1200px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .health-grid {
                grid-template-columns: 1fr;
            }

            .detail-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .detail-card-wide {
                grid-column: span 2;
            }

            .row-head {
                display: none;
            }

            .row-card {
                grid-template-columns: 1fr !important;
                gap: 6px;
                padding: 16px;
            }
        }

        @media (max-width: 800px) {
            .app {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-radius: 0 0 32px 32px;
            }

            .main {
                padding: 28px 20px;
            }

            .stat-grid,
            .filter-grid,
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .detail-card-wide {
                grid-column: span 1;
            }

            .health-row {
                flex-direction: column;
                align-items: stretch;
            }

            .wargaku-confirm-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
<div class="app">
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand brand-with-logo">
            <div class="brand-logo-card">
                <img
                    src="{{ asset('images/logo-diskominfo.png') }}"
                    alt="Logo Dinas Komunikasi dan Informatika"
                    class="brand-logo-image"
                >
            </div>

            <div class="brand-app-name">
                Wargaku Bridging API
            </div>
        </a>

        <nav class="nav">
            <a
                href="{{ route('admin.dashboard') }}"
                class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            >
                <span class="dot"></span>
                Dashboard
            </a>

            <a
                href="{{ route('admin.api-configs.index') }}"
                class="nav-item {{ request()->routeIs('admin.api-configs.*') ? 'active' : '' }}"
            >
                <span class="dot"></span>
                API Configs
            </a>

            <a
                href="{{ route('admin.api-logs.index') }}"
                class="nav-item {{ request()->routeIs('admin.api-logs.*') ? 'active' : '' }}"
            >
                <span class="dot"></span>
                API Logs
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="status-pill">
                <span class="pulse"></span>
                Target aktif
            </div>

            <div class="status-target">
                {{ $targetLabel }}
            </div>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <div>
                <h1 class="page-title">
                    @yield('page_title', 'Dashboard')
                </h1>

                <div class="page-date" id="admin-clock" data-timezone="Asia/Jakarta">
                    {{ now('Asia/Jakarta')->format('d/m/Y · H:i') }} WIB
                </div>
            </div>

            <div class="avatar">
                A
            </div>
        </div>

        @if (session('success'))
            <div class="flash-message success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="flash-message error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<div class="wargaku-confirm-overlay" id="wargaku-confirm-overlay">
    <div class="wargaku-confirm-modal danger" id="wargaku-confirm-modal">
        <button
            type="button"
            class="wargaku-confirm-close"
            onclick="closeWargakuConfirmModal()"
        >
            ✕
        </button>

        <div class="wargaku-confirm-icon" id="wargaku-confirm-icon">
            🗑️
        </div>

        <h3 class="wargaku-confirm-title" id="wargaku-confirm-title">
            Hapus semua log?
        </h3>

        <p class="wargaku-confirm-desc" id="wargaku-confirm-desc">
            Semua data API log akan dihapus permanen dari sistem.
        </p>

        <div class="wargaku-confirm-tag" id="wargaku-confirm-tag">
            <span class="d"></span>
            <span id="wargaku-confirm-tag-text">
                Data tidak bisa dikembalikan
            </span>
        </div>

        <div class="wargaku-confirm-actions">
            <button
                type="button"
                class="modal-btn modal-btn-cancel"
                onclick="closeWargakuConfirmModal()"
            >
                Batal
            </button>

            <button
                type="button"
                class="modal-btn modal-btn-danger"
                id="wargaku-confirm-submit"
            >
                Ya, hapus
            </button>
        </div>
    </div>
</div>

<script>
    function updateAdminClock() {
        const clockElement = document.getElementById('admin-clock');

        if (!clockElement) {
            return;
        }

        const timezone = clockElement.dataset.timezone || 'Asia/Jakarta';

        const formatter = new Intl.DateTimeFormat('id-ID', {
            timeZone: timezone,
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        const parts = formatter.formatToParts(new Date());

        const day = parts.find(part => part.type === 'day')?.value || '00';
        const month = parts.find(part => part.type === 'month')?.value || '00';
        const year = parts.find(part => part.type === 'year')?.value || '0000';
        const hour = parts.find(part => part.type === 'hour')?.value || '00';
        const minute = parts.find(part => part.type === 'minute')?.value || '00';

        clockElement.textContent = `${day}/${month}/${year} · ${hour}:${minute} WIB`;
    }

    updateAdminClock();
    setInterval(updateAdminClock, 1000);
</script>

<script>
    let wargakuPendingDeleteForm = null;

    function openWargakuDeleteModal(form, type, name = null) {
        wargakuPendingDeleteForm = form;

        const overlay = document.getElementById('wargaku-confirm-overlay');
        const modal = document.getElementById('wargaku-confirm-modal');
        const icon = document.getElementById('wargaku-confirm-icon');
        const title = document.getElementById('wargaku-confirm-title');
        const desc = document.getElementById('wargaku-confirm-desc');
        const tagText = document.getElementById('wargaku-confirm-tag-text');
        const submitButton = document.getElementById('wargaku-confirm-submit');

        modal.classList.remove('danger', 'warn');
        submitButton.classList.remove('modal-btn-danger', 'modal-btn-warn');

        if (type === 'failed') {
            modal.classList.add('warn');
            submitButton.classList.add('modal-btn-warn');

            icon.textContent = '⚠️';
            title.textContent = 'Hapus log gagal?';
            desc.textContent = 'Hanya log dengan status request gagal yang akan dihapus. Log sukses tetap tersimpan.';
            tagText.textContent = 'Data log gagal tidak bisa dikembalikan';
            submitButton.textContent = 'Ya, hapus log gagal';
        } else if (type === 'api-config') {
            modal.classList.add('danger');
            submitButton.classList.add('modal-btn-danger');

            icon.textContent = '🗑️';
            title.textContent = 'Hapus endpoint?';
            desc.textContent = name
                ? `Endpoint "${name}" akan dihapus dari API Config. Request ke endpoint ini tidak akan bisa diproses lagi.`
                : 'Endpoint ini akan dihapus dari API Config. Request ke endpoint ini tidak akan bisa diproses lagi.';
            tagText.textContent = 'Endpoint yang dihapus tidak bisa digunakan lagi';
            submitButton.textContent = 'Ya, hapus endpoint';
        } else {
            modal.classList.add('danger');
            submitButton.classList.add('modal-btn-danger');

            icon.textContent = '🗑️';
            title.textContent = 'Hapus semua log?';
            desc.textContent = 'Semua data API log — termasuk yang sukses dan gagal — akan dihapus permanen dari sistem.';
            tagText.textContent = 'Data tidak bisa dikembalikan';
            submitButton.textContent = 'Ya, hapus semua';
        }

        overlay.classList.add('show');

        return false;
    }
    function closeWargakuConfirmModal() {
        wargakuPendingDeleteForm = null;

        const overlay = document.getElementById('wargaku-confirm-overlay');

        if (overlay) {
            overlay.classList.remove('show');
        }
    }

    document.getElementById('wargaku-confirm-submit').addEventListener('click', function () {
        if (wargakuPendingDeleteForm) {
            wargakuPendingDeleteForm.submit();
        }
    });

    document.getElementById('wargaku-confirm-overlay').addEventListener('click', function (event) {
        if (event.target.id === 'wargaku-confirm-overlay') {
            closeWargakuConfirmModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeWargakuConfirmModal();
        }
    });
</script>

@stack('scripts')
</body>
</html>