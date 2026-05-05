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
            margin-bottom: 20px;
            font-size: 2rem;
            color: #fff;
            text-shadow: 0 0 10px #5c5cff;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-box input {
            padding: 10px;
            border-radius: 10px;
            border: none;
            outline: none;
            background: #2a2a3d;
            color: #fff;
            width: 250px;
        }

        .btn {
            padding: 12px 20px;
            background: #5c5cff;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
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
        }

        .badge {
            padding: 5px 10px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 5px;
        }

        .creator { background: #00ff7f33; color: #00ff7f; }
        .updater { background: #ffd70033; color: #ffd700; }
        .deleter { background: #ff4c4c33; color: #ff4c4c; }

        .actions {
            margin-top: 10px;
        }

        .edit-btn {
            background: orange;
            padding: 6px 10px;
            border-radius: 6px;
            color: #fff;
            text-decoration: none;
        }

        .delete-btn {
            background: red;
            padding: 6px 10px;
            border-radius: 6px;
            color: #fff;
            border: none;
        }

        .pagination {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="container">

    <h2>Products Dashboard</h2>

    <!-- TOP BAR -->
    <div class="top-bar">
        <a href="{{ route('products.create') }}" class="btn">➕ Create Product</a>

        <!-- SEARCH -->
        <form method="GET" class="search-box">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        </form>
    </div>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="success-msg">
            {{ session('success') }}
        </div>
    @endif

    <!-- CARDS -->
    <div class="cards">
        @foreach($products as $product)
            <div class="card">
                <h3>{{ $product->name }}</h3>
                <p><strong>ID:</strong> {{ $product->id }}</p>
                <p><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>

                <span class="badge creator">
                    Creator: {{ $product->managers->creator?->name ?? 'N/A' }}
                </span>

                <span class="badge updater">
                    Updater: {{ $product->managers->updater?->name ?? 'N/A' }}
                </span>

                <span class="badge deleter">
                    Deleter: {{ $product->managers->deleter?->name ?? 'N/A' }}
                </span>

                <!-- ACTIONS -->
                <div class="actions">
                    <a href="{{ route('products.edit',$product->id) }}" class="edit-btn">Edit</a>

                    <form action="{{ route('products.destroy',$product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="delete-btn" onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div class="pagination">
        {{ $products->links() }}
    </div>

</div>

</body>
</html>