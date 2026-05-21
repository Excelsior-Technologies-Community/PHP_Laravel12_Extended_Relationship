<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Product</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1e2f, #121223);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #fff;
        }

        .card {
            width: 450px;
            background: #2a2a3d;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        h2 {
            text-align: center;
            color: #5c5cff;
            margin-bottom: 20px;
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
            height: 80px;
            resize: none;
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

        .error {
            background: #ff4c4c33;
            color: #ff4c4c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 13px;
        }
    </style>

</head>

<body>

    <div class="card">

        <a href="{{ route('products.index') }}" class="back">← Back to Dashboard</a>

        <h2>Create Product</h2>

        @if($errors->any())
        @foreach($errors->all() as $error)
        <div class="error">{{ $error }}</div>
        @endforeach
        @endif

        <form action="{{ route('products.store') }}" method="POST">

            @csrf

            <label>Product Name</label>
            <input type="text" name="name" placeholder="Enter product name" required>

            <label>Description</label>
            <textarea name="description" placeholder="Enter product description"></textarea>

            <label>Creator</label>
            <select name="created_by">
                <option value="">Select Manager</option>
                @foreach($managers as $manager)
                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>

            <label>Updater</label>
            <select name="updated_by">
                <option value="">Select Manager</option>
                @foreach($managers as $manager)
                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>

            <label>Deleter</label>
            <select name="deleted_by">
                <option value="">Select Manager</option>
                @foreach($managers as $manager)
                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>

            <label>Status</label>
            <select name="status">
                <option value="Active">Active</option>
                <option value="In Review">In Review</option>
                <option value="Archived">Archived</option>
            </select>

            <button type="submit">Create Product</button>

        </form>

    </div>

</body>

</html>