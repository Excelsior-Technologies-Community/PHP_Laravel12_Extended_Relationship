<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #121223); color: #e0e0e0; display: flex; justify-content: center; padding: 40px 20px; }
        .card { background: #2a2a3d; padding: 30px 35px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.6); width: 420px; }
        h2 { text-align: center; margin-bottom: 20px; color: #5c5cff; }
        label { display: block; margin-top: 14px; margin-bottom: 5px; font-weight: 600; font-size: 13px; }
        input, select { width: 100%; padding: 10px; border-radius: 8px; border: none; background: #3a3a5c; color: #fff; font-size: 14px; outline: none; }
        select[multiple] { height: 100px; }
        input:focus, select:focus { background: #4a4a70; box-shadow: 0 0 8px #5c5cff55; }
        button { margin-top: 18px; width: 100%; padding: 12px; border: none; border-radius: 10px; background: #5c5cff; color: #fff; font-weight: 600; cursor: pointer; transition: 0.3s; }
        button:hover { background: #3e3eff; }
        .back { display: block; text-align: center; margin-bottom: 15px; color: #aaa; text-decoration: none; font-size: 13px; }
        .back:hover { color: #fff; }
        .hint { font-size: 11px; color: #888; margin-top: 3px; }
    </style>
</head>
<body>
<div class="card">
    <a href="{{ route('managers.index') }}" class="back">← Back to Managers</a>
    <h2>Edit Manager</h2>

    <form action="{{ route('managers.update', $manager->id) }}" method="POST">
        @csrf @method('PUT')

        <label>Manager Name</label>
        <input type="text" name="name" value="{{ old('name', $manager->name) }}" required>

        @if($tags->count())
        <label>Assign Tags</label>
        <select name="tag_ids[]" multiple>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ $manager->tags->contains($tag->id) ? 'selected' : '' }}>
                    {{ $tag->name }} @if($tag->category)({{ $tag->category }})@endif
                </option>
            @endforeach
        </select>
        <p class="hint">Hold Ctrl / Cmd to select multiple</p>
        @endif

        <button type="submit">Update Manager</button>
    </form>
</div>
</body>
</html>
