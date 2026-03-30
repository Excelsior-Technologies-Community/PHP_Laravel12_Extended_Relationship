<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Products Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1e2f 0%, #121223 100%);
            color: #e0e0e0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            color: #fff;
            text-shadow: 0 0 10px #5c5cff;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            margin-bottom: 20px;
            background: #5c5cff;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 0 15px #5c5cff55;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #3e3eff;
            box-shadow: 0 0 25px #3e3eff88;
        }

        .success-msg {
            text-align: center;
            margin-bottom: 20px;
            padding: 12px 20px;
            border-radius: 10px;
            background: #00ff7f33;
            color: #00ff7f;
            font-weight: 600;
            text-shadow: 0 0 8px #00ff7f;
            box-shadow: 0 0 15px #00ff7f44;
            animation: glow 1.5s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 10px #00ff7f33;
            }

            to {
                box-shadow: 0 0 25px #00ff7f77;
            }
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .card {
            background-color: #2a2a3d;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
        }

        .card h3 {
            margin-top: 0;
            color: #5c5cff;
            font-size: 1.5rem;
        }

        .card p {
            margin: 8px 0;
            font-size: 0.95rem;
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 5px;
            margin-right: 5px;
        }

        .creator {
            background-color: #00ff7f33;
            color: #00ff7f;
            text-shadow: 0 0 4px #00ff7f77;
        }

        .updater {
            background-color: #ffd70033;
            color: #ffd700;
            text-shadow: 0 0 4px #ffd70077;
        }

        .deleter {
            background-color: #ff4c4c33;
            color: #ff4c4c;
            text-shadow: 0 0 4px #ff4c4c77;
        }

        .timestamp {
            font-size: 0.85rem;
            color: #bbb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Products Dashboard</h2>
        <a href="{{ route('products.create') }}" class="btn">➕ Create Product</a>

        @if(session('success'))
            <div class="success-msg">
                {{ session('success') }}
            </div>
        @endif

        <div class="cards">
            @foreach($products as $product)
                <div class="card">
                    <h3>{{ $product->name }}</h3>
                    <p><strong>ID:</strong> {{ $product->id }}</p>
                    <p><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>

                    <p>
                        <span class="badge creator">Creator: {{ $product->managers->creator?->name ?? 'N/A' }}</span>

                    </p>
                    <p>
                        <span class="badge updater">Updater: {{ $product->managers->updater?->name ?? 'N/A' }}</span>
                    </p>
                    <p>
                        <span class="badge deleter">Deleter: {{ $product->managers->deleter?->name ?? 'N/A' }}</span>
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>