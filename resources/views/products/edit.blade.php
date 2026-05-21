<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1e2f, #121223);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            width: 450px;
            background: #2a2a3d;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #5c5cff;
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 14px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #3a3a5c;
            color: #fff;
            outline: none;
            font-size: 14px;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        input:focus,
        textarea:focus,
        select:focus {
            background: #4a4a70;
            box-shadow: 0 0 10px #5c5cff55;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #5c5cff;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #3e3eff;
            box-shadow: 0 0 20px #3e3eff77;
        }

        .back {
            display: block;
            text-align: center;
            margin-bottom: 15px;
            color: #aaa;
            text-decoration: none;
        }

        .back:hover {
            color: #fff;
        }
    </style>

</head>

<body>

    <div class="card">

        <a href="{{ route('products.index') }}" class="back">← Back to Dashboard</a>

        <h2>Edit Product</h2>

        <form action="{{ route('products.update',$product->id) }}" method="POST">

            @csrf
            @method('PUT')

            <label>Product Name</label>
            <input type="text" name="name" value="{{ $product->name }}">

            <label>Description</label>
            <textarea name="description">{{ $product->description }}</textarea>

            <label>Creator</label>
            <select name="created_by">
                @foreach($managers as $m)
                <option value="{{ $m->id }}" {{ $product->created_by == $m->id ? 'selected':'' }}>
                    {{ $m->name }}
                </option>
                @endforeach
            </select>

            <label>Status</label>
            <select name="status">
                <option value="Active" {{ $product->status=='Active'?'selected':'' }}>Active</option>
                <option value="In Review" {{ $product->status=='In Review'?'selected':'' }}>In Review</option>
                <option value="Archived" {{ $product->status=='Archived'?'selected':'' }}>Archived</option>
            </select>

            <button type="submit">Update Product</button>

        </form>

    </div>

</body>

</html>