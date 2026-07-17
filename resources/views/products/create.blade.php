<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #1e1e2f, #121223); color: #e0e0e0; display: flex; justify-content: center; padding: 40px 20px; }
        .card { background: #2a2a3d; padding: 30px 35px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.6); width: 450px; }
        h2 { text-align: center; margin-bottom: 20px; color: #5c5cff; text-shadow: 0 0 10px #5c5cff77; }
        label { display: block; margin-top: 14px; margin-bottom: 5px; font-weight: 600; font-size: 13px; }
        input, select, textarea { width: 100%; padding: 10px; border-radius: 8px; border: none; background: #3a3a5c; color: #fff; font-size: 14px; outline: none; }
        select[multiple] { height: 100px; }
        input:focus, select:focus, textarea:focus { background: #4a4a70; box-shadow: 0 0 8px #5c5cff55; }
        button { margin-top: 18px; width: 100%; padding: 12px; border: none; border-radius: 10px; background: #5c5cff; color: #fff; font-weight: 600; cursor: pointer; transition: 0.3s; }
        button:hover { background: #3e3eff; }
        .back { display: block; text-align: center; margin-bottom: 15px; color: #aaa; text-decoration: none; font-size: 13px; }
        .back:hover { color: #fff; }
        .error-list { background: #ff4c4c22; padding: 10px 15px; border-radius: 8px; margin-bottom: 12px; }
        .error-list li { color: #ff4c4c; font-size: 13px; }
        .hint { font-size: 11px; color: #888; margin-top: 3px; }
    </style>
</head>
<body>
<div class="card">
    <a href="{{ route('products.index') }}" class="back">← Back to Dashboard</a>
    <h2>Create Product</h2>

    @if($errors->any())
    <div class="error-list"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <label>Product Name</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter product name" required>

        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Enter description">{{ old('description') }}</textarea>

        <label>Creator</label>
        <select name="created_by">
            <option value="">-- Select Manager --</option>
            @foreach($managers as $m)
                <option value="{{ $m->id }}" {{ old('created_by') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
            @endforeach
        </select>

        <label>Updater</label>
        <select name="updated_by">
            <option value="">-- Select Manager --</option>
            @foreach($managers as $m)
                <option value="{{ $m->id }}" {{ old('updated_by') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
            @endforeach
        </select>

        <label>Deleter</label>
        <select name="deleted_by">
            <option value="">-- Select Manager --</option>
            @foreach($managers as $m)
                <option value="{{ $m->id }}" {{ old('deleted_by') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
            @endforeach
        </select>

        <label>Status</label>
        <select name="status">
            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="In Review" {{ old('status') == 'In Review' ? 'selected' : '' }}>In Review</option>
            <option value="Archived" {{ old('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
        </select>

        @if($tags->count())
        <label>Tags</label>
        <select name="tag_ids[]" multiple>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tag_ids', [])) ? 'selected' : '' }}>
                    {{ $tag->name }} @if($tag->category)({{ $tag->category }})@endif
                </option>
            @endforeach
        </select>
        <p class="hint">Hold Ctrl / Cmd to select multiple tags</p>
        @endif

        <button type="submit">Create Product</button>
    </form>
</div>
</body>
</html>
