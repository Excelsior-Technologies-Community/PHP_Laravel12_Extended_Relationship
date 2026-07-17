<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tags</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #121223); color: #e0e0e0; }
        .container { max-width: 900px; margin: 40px auto; padding: 20px; }
        h2 { text-align: center; color: #fff; text-shadow: 0 0 10px #5c5cff; margin-bottom: 20px; }
        .nav { display: flex; justify-content: center; gap: 12px; margin-bottom: 25px; flex-wrap: wrap; }
        .nav a { padding: 8px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px; background: #2a2a3d; color: #aaa; transition: 0.3s; }
        .nav a.active, .nav a:hover { background: #5c5cff; color: #fff; }
        .success-msg { background: #00ff7f33; color: #00ff7f; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; }
        .layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .panel { background: #2a2a3d; border-radius: 15px; padding: 25px; }
        .panel h3 { margin-top: 0; color: #5c5cff; font-size: 16px; }
        label { display: block; margin-top: 12px; margin-bottom: 5px; font-weight: 600; font-size: 13px; }
        input { width: 100%; padding: 10px; border-radius: 8px; border: none; background: #3a3a5c; color: #fff; font-size: 14px; outline: none; }
        input:focus { background: #4a4a70; box-shadow: 0 0 8px #5c5cff55; }
        .color-row { display: flex; gap: 10px; align-items: center; }
        .color-row input[type=color] { width: 50px; height: 40px; padding: 2px; border-radius: 8px; cursor: pointer; }
        .color-row input[type=text] { flex: 1; }
        button.submit { margin-top: 16px; width: 100%; padding: 11px; border: none; border-radius: 10px; background: #5c5cff; color: #fff; font-weight: 600; cursor: pointer; transition: 0.3s; }
        button.submit:hover { background: #3e3eff; }
        .tag-list { display: flex; flex-direction: column; gap: 10px; }
        .tag-row { display: flex; justify-content: space-between; align-items: center; background: #1e1e2f; padding: 10px 14px; border-radius: 10px; }
        .tag-info { display: flex; align-items: center; gap: 10px; }
        .tag-dot { width: 14px; height: 14px; border-radius: 50%; flex-shrink: 0; }
        .tag-name { font-weight: 600; font-size: 14px; }
        .tag-cat { font-size: 11px; color: #888; }
        .tag-count { font-size: 12px; color: #aaa; }
        .delete-btn { background: red; padding: 5px 10px; border-radius: 6px; color: #fff; border: none; cursor: pointer; font-size: 12px; }
        .error-list { background: #ff4c4c22; padding: 10px 15px; border-radius: 8px; margin-bottom: 12px; }
        .error-list li { color: #ff4c4c; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <h2>🏷️ Tags Management</h2>

    <div class="nav">
        <a href="{{ route('products.index') }}">📦 Products</a>
        <a href="{{ route('managers.index') }}">👤 Managers</a>
        <a href="{{ route('tags.index') }}" class="active">🏷️ Tags</a>
        <a href="{{ route('telemetry.index') }}">📊 Telemetry</a>
    </div>

    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    <div class="layout">

        <!-- CREATE TAG -->
        <div class="panel">
            <h3>Create New Tag</h3>

            @if($errors->any())
            <div class="error-list"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form action="{{ route('tags.store') }}" method="POST">
                @csrf
                <label>Tag Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Urgent" required>

                <label>Category (optional)</label>
                <input type="text" name="category" value="{{ old('category') }}" placeholder="e.g. priority, region">

                <label>Color</label>
                <div class="color-row">
                    <input type="color" id="colorPicker" value="{{ old('color', '#5c5cff') }}" oninput="document.getElementById('colorText').value=this.value">
                    <input type="text" id="colorText" name="color" value="{{ old('color', '#5c5cff') }}" oninput="document.getElementById('colorPicker').value=this.value">
                </div>

                <button type="submit" class="submit">Create Tag</button>
            </form>
        </div>

        <!-- TAG LIST -->
        <div class="panel">
            <h3>All Tags ({{ $tags->count() }})</h3>
            <div class="tag-list">
                @forelse($tags as $tag)
                <div class="tag-row">
                    <div class="tag-info">
                        <div class="tag-dot" style="background:{{ $tag->color }};"></div>
                        <div>
                            <div class="tag-name">{{ $tag->name }}</div>
                            @if($tag->category)<div class="tag-cat">{{ $tag->category }}</div>@endif
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span class="tag-count">📦{{ $tag->products_count }} 👤{{ $tag->managers_count }}</span>
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="delete-btn" onclick="return confirm('Delete tag?')">🗑</button>
                        </form>
                    </div>
                </div>
                @empty
                <p style="color:#666; text-align:center;">No tags yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
</body>
</html>
