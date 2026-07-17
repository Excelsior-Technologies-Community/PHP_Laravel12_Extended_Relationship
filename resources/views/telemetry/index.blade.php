<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Telemetry Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #121223); color: #e0e0e0; }
        .container { max-width: 1100px; margin: 40px auto; padding: 20px; }
        h2 { text-align: center; color: #fff; text-shadow: 0 0 10px #5c5cff; margin-bottom: 8px; }
        .subtitle { text-align: center; color: #888; font-size: 13px; margin-bottom: 25px; }
        .nav { display: flex; justify-content: center; gap: 12px; margin-bottom: 25px; flex-wrap: wrap; }
        .nav a { padding: 8px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px; background: #2a2a3d; color: #aaa; transition: 0.3s; }
        .nav a.active, .nav a:hover { background: #5c5cff; color: #fff; }

        /* METRIC CARDS */
        .metrics { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 30px; }
        .metric { background: #2a2a3d; border-radius: 15px; padding: 20px; text-align: center; position: relative; overflow: hidden; }
        .metric::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
        .metric.green::before { background: #00ff7f; }
        .metric.red::before { background: #ff4c4c; }
        .metric.yellow::before { background: #ffd700; }
        .metric.blue::before { background: #5c5cff; }
        .metric.purple::before { background: #c084fc; }
        .metric h3 { margin: 0 0 6px; font-size: 28px; font-weight: 700; }
        .metric.green h3 { color: #00ff7f; }
        .metric.red h3 { color: #ff4c4c; }
        .metric.yellow h3 { color: #ffd700; }
        .metric.blue h3 { color: #5c5cff; }
        .metric.purple h3 { color: #c084fc; }
        .metric p { margin: 0; font-size: 12px; color: #aaa; }

        /* N+1 ALERT */
        .alert { border-radius: 12px; padding: 16px 20px; margin-bottom: 25px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 12px; }
        .alert.danger { background: #ff4c4c22; border: 1px solid #ff4c4c55; color: #ff4c4c; }
        .alert.safe { background: #00ff7f22; border: 1px solid #00ff7f55; color: #00ff7f; }
        .alert-icon { font-size: 22px; }

        /* COMPARISON TABLE */
        .section-title { font-size: 15px; font-weight: 700; color: #5c5cff; margin: 25px 0 12px; }
        .compare { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        .compare-box { background: #2a2a3d; border-radius: 12px; padding: 18px; }
        .compare-box h4 { margin: 0 0 12px; font-size: 14px; }
        .compare-box.eager h4 { color: #00ff7f; }
        .compare-box.lazy h4 { color: #ff4c4c; }
        .compare-box .num { font-size: 36px; font-weight: 700; }
        .compare-box.eager .num { color: #00ff7f; }
        .compare-box.lazy .num { color: #ff4c4c; }
        .compare-box p { margin: 4px 0 0; font-size: 12px; color: #888; }

        /* PROGRESS BAR */
        .bar-wrap { background: #1e1e2f; border-radius: 10px; height: 10px; margin: 8px 0; overflow: hidden; }
        .bar { height: 100%; border-radius: 10px; transition: width 1s ease; }

        /* QUERY LOG */
        .log-section { background: #2a2a3d; border-radius: 12px; padding: 18px; margin-bottom: 20px; }
        .log-section h4 { margin: 0 0 12px; font-size: 14px; color: #5c5cff; }
        .log-item { background: #1e1e2f; border-radius: 8px; padding: 10px 14px; margin-bottom: 8px; font-size: 12px; font-family: monospace; color: #ccc; word-break: break-all; }
        .log-item .time { color: #ffd700; font-size: 11px; margin-top: 4px; }
        .log-toggle { background: none; border: 1px solid #3a3a5c; color: #aaa; padding: 6px 14px; border-radius: 8px; cursor: pointer; font-size: 12px; margin-bottom: 10px; }
        .log-toggle:hover { border-color: #5c5cff; color: #fff; }
        .log-body { display: none; }
        .log-body.open { display: block; }
    </style>
</head>
<body>
<div class="container">

    <h2>📊 Telemetry Dashboard</h2>
    <p class="subtitle">Multi-Level Eager Loading Performance Optimizer — N+1 Query Detection & Memory Efficiency Tracker</p>

    <div class="nav">
        <a href="{{ route('products.index') }}">📦 Products</a>
        <a href="{{ route('managers.index') }}">👤 Managers</a>
        <a href="{{ route('tags.index') }}">🏷️ Tags</a>
        <a href="{{ route('telemetry.index') }}" class="active">📊 Telemetry</a>
    </div>

    <!-- N+1 ALERT -->
    @if($metrics['n1_detected'])
    <div class="alert danger">
        <span class="alert-icon">⚠️</span>
        <div>
            <strong>N+1 Query Problem Detected!</strong><br>
            <span style="font-weight:400; font-size:13px;">
                Lazy loading triggered <strong>{{ $metrics['lazy_query_count'] }}</strong> queries for {{ $metrics['product_count'] }} products.
                Eager loading reduces this to <strong>{{ $metrics['eager_query_count'] }}</strong> queries.
                You save <strong>{{ $metrics['query_saved'] }}</strong> queries by using <code>with()</code>.
            </span>
        </div>
    </div>
    @else
    <div class="alert safe">
        <span class="alert-icon">✅</span>
        <strong>No N+1 Issue Detected</strong> — Eager loading is working efficiently.
    </div>
    @endif

    <!-- METRIC COUNTERS -->
    <div class="metrics">
        <div class="metric blue">
            <h3>{{ $metrics['product_count'] }}</h3>
            <p>Total Products Tested</p>
        </div>
        <div class="metric green">
            <h3>{{ $metrics['eager_query_count'] }}</h3>
            <p>Eager Load Queries</p>
        </div>
        <div class="metric red">
            <h3>{{ $metrics['lazy_query_count'] }}</h3>
            <p>Lazy Load Queries (N+1)</p>
        </div>
        <div class="metric yellow">
            <h3>{{ $metrics['query_saved'] }}</h3>
            <p>Queries Saved</p>
        </div>
        <div class="metric purple">
            <h3>{{ $metrics['manager_queries'] }}</h3>
            <p>Manager Layer Queries</p>
        </div>
    </div>

    <!-- COMPARISON -->
    <div class="section-title">⚡ Eager vs Lazy Load Comparison</div>
    <div class="compare">
        <div class="compare-box eager">
            <h4>✅ Eager Loading — with('managers', 'tags')</h4>
            <div class="num">{{ $metrics['eager_query_count'] }}</div>
            <p>Total SQL queries executed</p>
            <div class="bar-wrap">
                @php $eagerPct = $metrics['lazy_query_count'] > 0 ? round(($metrics['eager_query_count'] / $metrics['lazy_query_count']) * 100) : 100; @endphp
                <div class="bar" style="width:{{ $eagerPct }}%; background:#00ff7f;"></div>
            </div>
            <p>Memory: <strong>{{ $metrics['eager_memory_kb'] }} KB</strong></p>
        </div>
        <div class="compare-box lazy">
            <h4>❌ Lazy Loading — N+1 Problem</h4>
            <div class="num">{{ $metrics['lazy_query_count'] }}</div>
            <p>Total SQL queries executed</p>
            <div class="bar-wrap">
                <div class="bar" style="width:100%; background:#ff4c4c;"></div>
            </div>
            <p>Memory: <strong>{{ $metrics['lazy_memory_kb'] }} KB</strong></p>
        </div>
    </div>

    <!-- MEMORY SECTION -->
    <div class="section-title">🧠 Memory Efficiency</div>
    <div class="metrics" style="grid-template-columns: repeat(3, 1fr);">
        <div class="metric green">
            <h3>{{ $metrics['eager_memory_kb'] }} KB</h3>
            <p>Eager Load Memory</p>
        </div>
        <div class="metric red">
            <h3>{{ $metrics['lazy_memory_kb'] }} KB</h3>
            <p>Lazy Load Memory</p>
        </div>
        <div class="metric yellow">
            <h3>{{ $metrics['memory_diff_kb'] }} KB</h3>
            <p>Memory Difference</p>
        </div>
    </div>

    <!-- EAGER QUERY LOG -->
    <div class="log-section">
        <h4>✅ Eager Load Query Log ({{ $metrics['eager_query_count'] }} queries)</h4>
        <button class="log-toggle" onclick="this.nextElementSibling.classList.toggle('open'); this.textContent = this.nextElementSibling.classList.contains('open') ? 'Hide Queries ▲' : 'Show Queries ▼'">Show Queries ▼</button>
        <div class="log-body">
            @foreach($metrics['eager_queries_log'] as $q)
            <div class="log-item">
                {{ $q['query'] }}
                <div class="time">⏱ {{ round($q['time'], 2) }} ms</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- LAZY QUERY LOG -->
    <div class="log-section">
        <h4>❌ Lazy Load Query Log ({{ $metrics['lazy_query_count'] }} queries)</h4>
        <button class="log-toggle" onclick="this.nextElementSibling.classList.toggle('open'); this.textContent = this.nextElementSibling.classList.contains('open') ? 'Hide Queries ▲' : 'Show Queries ▼'">Show Queries ▼</button>
        <div class="log-body">
            @foreach($metrics['lazy_queries_log'] as $q)
            <div class="log-item">
                {{ $q['query'] }}
                <div class="time">⏱ {{ round($q['time'], 2) }} ms</div>
            </div>
            @endforeach
        </div>
    </div>

</div>
</body>
</html>
