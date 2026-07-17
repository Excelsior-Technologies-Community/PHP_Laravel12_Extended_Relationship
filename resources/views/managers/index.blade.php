<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Managers</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #121223); color: #e0e0e0; }
        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        h2 { text-align: center; color: #fff; text-shadow: 0 0 10px #5c5cff; margin-bottom: 20px; }
        .nav { display: flex; justify-content: center; gap: 12px; margin-bottom: 25px; flex-wrap: wrap; }
        .nav a { padding: 8px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 13px; transition: 0.3s; background: #2a2a3d; color: #aaa; }
        .nav a.active, .nav a:hover { background: #5c5cff; color: #fff; }
        .top-bar { display: flex; justify-content: flex-end; margin-bottom: 20px; }
        .btn { padding: 10px 18px; background: #5c5cff; color: #fff; text-decoration: none; border-radius: 10px; font-weight: 600; }
        .btn:hover { background: #3e3eff; }
        .success-msg { background: #00ff7f33; color: #00ff7f; padding: 12px; border-radius: 10px; margin-bottom: 20px; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #3a3a5c; font-size: 14px; }
        th { background: #2a2a3d; color: #5c5cff; font-weight: 700; }
        tr:hover td { background: #2a2a3d55; }
        .tag-badge { border-radius: 20px; padding: 3px 10px; font-size: 11px; font-weight: 700; margin: 2px; display: inline-block; }
        .actions { display: flex; gap: 8px; }
        .edit-btn { background: orange; padding: 5px 12px; border-radius: 6px; color: #fff; text-decoration: none; font-size: 12px; }
        .delete-btn { background: red; padding: 5px 12px; border-radius: 6px; color: #fff; border: none; cursor: pointer; font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Managers</h2>

    <div class="nav">
        <a href="{{ route('products.index') }}">📦 Products</a>
        <a href="{{ route('managers.index') }}" class="active">👤 Managers</a>
        <a href="{{ route('tags.index') }}">🏷️ Tags</a>
        <a href="{{ route('telemetry.index') }}">📊 Telemetry</a>
    </div>

    <div class="top-bar">
        <a href="{{ route('managers.create') }}" class="btn">➕ Add Manager</a>
    </div>

    @if(session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Tags</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($managers as $manager)
            <tr>
                <td>{{ $manager->id }}</td>
                <td>{{ $manager->name }}</td>
                <td>
                    @foreach($manager->tags as $tag)
                        <span class="tag-badge" style="background:{{ $tag->color }}33; color:{{ $tag->color }};">
                            🏷 {{ $tag->name }}
                        </span>
                    @endforeach
                    @if($manager->tags->isEmpty()) <span style="color:#666">—</span> @endif
                </td>
                <td>{{ $manager->created_at->format('d M Y') }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('managers.edit', $manager->id) }}" class="edit-btn">✏️ Edit</a>
                        <form action="{{ route('managers.destroy', $manager->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="delete-btn" onclick="return confirm('Delete manager?')">🗑 Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; color:#666;">No managers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
