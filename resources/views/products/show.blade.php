<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Detail</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #121223); color: #e0e0e0; min-height: 100vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 20px; }
        .card { background: #2a2a3d; border-radius: 20px; padding: 35px; width: 100%; max-width: 600px; box-shadow: 0 10px 40px rgba(0,0,0,0.6); }
        h2 { color: #5c5cff; text-align: center; margin-top: 0; text-shadow: 0 0 10px #5c5cff77; }
        .back { display: inline-block; margin-bottom: 20px; color: #aaa; text-decoration: none; font-size: 14px; }
        .back:hover { color: #fff; }
        .row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #3a3a5c; font-size: 14px; }
        .row:last-child { border-bottom: none; }
        .label { color: #aaa; font-weight: 600; }
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .creator { background: #00ff7f33; color: #00ff7f; }
        .updater { background: #ffd70033; color: #ffd700; }
        .deleter { background: #ff4c4c33; color: #ff4c4c; }
        .status  { background: #3498db33; color: #3498db; }
        .tag-badge { border-radius: 20px; padding: 5px 12px; font-size: 12px; font-weight: 700; margin: 3px; display: inline-block; }
        .tags-section { margin-top: 15px; }
        .actions { display: flex; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; border: none; cursor: pointer; }
        .btn-edit { background: orange; color: #fff; }
        .btn-back { background: #5c5cff; color: #fff; }
        .btn-del { background: red; color: #fff; }
    </style>
</head>
<body>
<div class="card">
    <a href="{{ route('products.index') }}" class="back">← Back to Dashboard</a>
    <h2>{{ $product->name }}</h2>

    <div class="row"><span class="label">ID</span><span>{{ $product->id }}</span></div>
    <div class="row"><span class="label">Description</span><span>{{ $product->description ?? 'N/A' }}</span></div>
    <div class="row"><span class="label">Status</span><span class="badge status">{{ $product->status }}</span></div>
    <div class="row"><span class="label">Created At</span><span>{{ $product->created_at->format('d M Y, H:i') }}</span></div>
    <div class="row"><span class="label">Updated At</span><span>{{ $product->updated_at->format('d M Y, H:i') }}</span></div>

    <div class="row">
        <span class="label">Creator</span>
        <span class="badge creator">{{ $product->managers->creator?->name ?? 'N/A' }}</span>
    </div>
    <div class="row">
        <span class="label">Updater</span>
        <span class="badge updater">{{ $product->managers->updater?->name ?? 'N/A' }}</span>
    </div>
    <div class="row">
        <span class="label">Deleter</span>
        <span class="badge deleter">{{ $product->managers->deleter?->name ?? 'N/A' }}</span>
    </div>

    @if($product->tags->count())
    <div class="tags-section">
        <div class="label" style="margin-bottom:8px;">Tags</div>
        @foreach($product->tags as $tag)
            <span class="tag-badge" style="background:{{ $tag->color }}33; color:{{ $tag->color }};">
                🏷 {{ $tag->name }} @if($tag->category) · {{ $tag->category }} @endif
            </span>
        @endforeach
    </div>
    @endif

    <div class="actions">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-edit">✏️ Edit</a>
        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
            @csrf @method('DELETE')
            <button class="btn btn-del" onclick="return confirm('Delete?')">🗑 Delete</button>
        </form>
    </div>
</div>
</body>
</html>
