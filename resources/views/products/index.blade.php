<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #121223); color: #e0e0e0; }
        .container { max-width: 1200px; margin: 40px auto; padding: 20px; }
        h2 { text-align: center; margin-bottom: 20px; font-size: 2rem; color: #fff; text-shadow: 0 0 10px #5c5cff; }
        .nav { display: flex; justify-content: center; gap: 12px; margin-bottom: 25px; flex-wrap: wrap; }
        .nav a { padding: 8px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px; transition: 0.3s; }
        .nav a.active, .nav a:hover { background: #5c5cff; color: #fff; }
        .nav a { background: #2a2a3d; color: #aaa; }
        .stats { display: flex; gap: 20px; margin-bottom: 25px; }
        .stat-card { flex: 1; background: #2a2a3d; padding: 20px; border-radius: 15px; text-align: center; }
        .stat-card h3 { margin: 0; font-size: 22px; color: #5c5cff; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 10px; flex-wrap: wrap; }
        .top-bar form { display: flex; gap: 8px; align-items: center; }
        .top-bar input, .top-bar select { padding: 10px; border-radius: 10px; border: none; outline: none; background: #2a2a3d; color: #fff; }
        .top-bar input { width: 200px; }
        .btn { padding: 10px 18px; background: #5c5cff; color: #fff; text-decoration: none; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; }
        .btn:hover { background: #3e3eff; }
        .success-msg { background: #00ff7f33; color: #00ff7f; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        .card { background: #2a2a3d; border-radius: 15px; padding: 20px; transition: 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin-top: 0; color: #5c5cff; }
        .badge { padding: 5px 10px; border-radius: 10px; display: inline-block; margin: 3px 2px; font-size: 12px; font-weight: 600; }
        .creator { background: #00ff7f33; color: #00ff7f; }
        .updater { background: #ffd70033; color: #ffd700; }
        .deleter { background: #ff4c4c33; color: #ff4c4c; }
        .status  { background: #3498db33; color: #3498db; }
        .tag-badge { border-radius: 20px; padding: 4px 10px; font-size: 11px; font-weight: 700; margin: 2px; display: inline-block; }
        .actions { margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap; }
        .edit-btn { background: orange; padding: 6px 12px; border-radius: 6px; color: #fff; text-decoration: none; font-size: 13px; }
        .delete-btn { background: red; padding: 6px 12px; border-radius: 6px; color: #fff; border: none; cursor: pointer; font-size: 13px; }
        .detail-btn { background: #5c5cff; padding: 6px 12px; border-radius: 6px; color: #fff; text-decoration: none; font-size: 13px; }
        .pagination-wrapper { margin-top: 40px; display: flex; justify-content: center; }
        .pagination { display: flex; gap: 8px; align-items: center; }
        .pagination a, .pagination span { padding: 8px 14px; border-radius: 10px; background: #2a2a3d; color: #e0e0e0; text-decoration: none; font-weight: 600; transition: 0.3s; min-width: 40px; text-align: center; }
        .pagination a:hover { background: #5c5cff; color: #fff; }
        .pagination .active { background: linear-gradient(135deg, #5c5cff, #7a7aff); color: #fff; box-shadow: 0 0 12px #5c5cff88; }
        .pagination .disabled { opacity: 0.3; pointer-events: none; }
    </style>
</head>
<body>
<div class="container">

    <h2>Products Dashboard</h2>

    <div class="nav">
        <a href="{{ route('products.index') }}" class="active">📦 Products</a>
        <a href="{{ route('managers.index') }}">👤 Managers</a>
        <a href="{{ route('tags.index') }}">🏷️ Tags</a>
        <a href="{{ route('telemetry.index') }}">📊 Telemetry</a>
    </div>

    <div class="stats">
        <div class="stat-card"><h3>{{ $totalProducts }}</h3><p>Total Products</p></div>
        <div class="stat-card"><h3>{{ $activeProducts }}</h3><p>Active Products</p></div>
        <div class="stat-card"><h3>{{ $totalManagers }}</h3><p>Total Managers</p></div>
    </div>

    <div class="top-bar">
        <a href="{{ route('products.create') }}" class="btn">➕ Create Product</a>
        <form method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products...">
            <select name="tag_id">
                <option value="">All Tags</option>
                @foreach($allTags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn">Filter</button>
        </form>
    </div>

    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    <div class="cards">
        @foreach($products as $product)
        <div class="card">
            <h3>{{ $product->name }}</h3>
            <p><strong>ID:</strong> {{ $product->id }}</p>
            <p><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>
            <div>
                <span class="badge creator">Creator: {{ $product->managers->creator?->name ?? 'N/A' }}</span>
                <span class="badge updater">Updater: {{ $product->managers->updater?->name ?? 'N/A' }}</span>
                <span class="badge deleter">Deleter: {{ $product->managers->deleter?->name ?? 'N/A' }}</span>
                <span class="badge status">{{ $product->status }}</span>
            </div>
            @if($product->tags->count())
            <div style="margin-top:8px;">
                @foreach($product->tags as $tag)
                    <span class="tag-badge" style="background:{{ $tag->color }}33; color:{{ $tag->color }};">
                        🏷 {{ $tag->name }}
                    </span>
                @endforeach
            </div>
            @endif
            <div class="actions">
                <a href="{{ route('products.show', $product->id) }}" class="detail-btn">👁 View</a>
                <a href="{{ route('products.edit', $product->id) }}" class="edit-btn">✏️ Edit</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="delete-btn" onclick="return confirm('Delete this product?')">🗑 Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination-wrapper">
        <div class="pagination">
            @if($products->onFirstPage())
                <span class="disabled">&lt;</span>
            @else
                <a href="{{ $products->previousPageUrl() }}">&lt;</a>
            @endif
            @for($i = 1; $i <= $products->lastPage(); $i++)
                @if($i == $products->currentPage())
                    <span class="active">{{ $i }}</span>
                @else
                    <a href="{{ $products->url($i) }}">{{ $i }}</a>
                @endif
            @endfor
            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}">&gt;</a>
            @else
                <span class="disabled">&gt;</span>
            @endif
        </div>
    </div>

</div>
</body>
</html>
