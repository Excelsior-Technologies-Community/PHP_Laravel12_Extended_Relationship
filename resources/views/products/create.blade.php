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
            background: linear-gradient(135deg, #1e1e2f 0%, #121223 100%);
            color: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background-color: #2a2a3d;
            padding: 30px 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.6);
            width: 400px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #5c5cff;
            text-shadow: 0 0 10px #5c5cff77;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            background-color: #3a3a5c;
            color: #fff;
            font-size: 14px;
            transition: background 0.3s, box-shadow 0.3s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            background-color: #4a4a70;
            box-shadow: 0 0 8px #5c5cff55;
        }

        button,
        .btn-back {
            margin-top: 15px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }

        button {
            background-color: #5c5cff;
            color: #fff;
            box-shadow: 0 0 15px #5c5cff55;
        }

        button:hover {
            background-color: #3e3eff;
            box-shadow: 0 0 25px #3e3eff77;
        }

        .btn-back {
            background-color: #8888ff33;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 10px;
            box-shadow: 0 0 10px #8888ff44;
        }

        .btn-back:hover {
            background-color: #5c5cff;
            box-shadow: 0 0 20px #5c5cff77;
        }

        .error-list {
            background-color: #ff4c4c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .error-list ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-list li {
            color: #ff4c4c;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Create Product</h2>

        <!-- Back Button -->
        <a href="{{ route('products.index') }}" class="btn-back">⬅ Back to Dashboard</a>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="error-list">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Product Form -->
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <label>Product Name</label>
            <input type="text" name="name" placeholder="Enter product name" required>

            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Enter product description"></textarea>

            <label>Creator</label>
            <select name="created_by">
                <option value="">-- Select Manager --</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>

            <label>Updater</label>
            <select name="updated_by">
                <option value="">-- Select Manager --</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>

            <label>Deleter</label>
            <select name="deleted_by">
                <option value="">-- Select Manager --</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>

            <button type="submit">Create Product</button>
        </form>
    </div>
</body>

</html>