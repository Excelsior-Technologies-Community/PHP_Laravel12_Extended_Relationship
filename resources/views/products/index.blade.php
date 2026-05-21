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
            background: linear-gradient(135deg, #1e1e2f, #121223);
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

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            flex: 1;
            background: #2a2a3d;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.5);
        }

        .stat-card h3 {
            margin: 0;
            font-size: 22px;
            color: #5c5cff;
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

        .success-msg {
            background: #00ff7f33;
            color: #00ff7f;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .card {
            background: #2a2a3d;
            border-radius: 15px;
            padding: 20px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .badge {
            padding: 6px 10px;
            border-radius: 10px;
            display: inline-block;
            margin-top: 6px;
            font-size: 13px;
        }

        .creator { background: #00ff7f33; color: #00ff7f; }
        .updater { background: #ffd70033; color: #ffd700; }
        .deleter { background: #ff4c4c33; color: #ff4c4c; }
        .status { background: #3498db33; color: #3498db; }

        .actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
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

        /* =========================
           ⭐ CUSTOM NUMBER PAGINATION
           ========================= */

        .pagination-wrapper {
            margin-top: 40px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            padding: 8px 14px;
            border-radius: 10px;
            background: #2a2a3d;
            color: #e0e0e0;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            min-width: 40px;
            text-align: center;
        }

        .pagination a:hover {
            background: #5c5cff;
            color: #fff;
            transform: translateY(-2px);
        }

        .pagination .active {
            background: linear-gradient(135deg, #5c5cff, #7a7aff);
            color: #fff;
            box-shadow: 0 0 12px #5c5cff88;
        }

        .pagination .disabled {
            opacity: 0.3;
            pointer-events: none;
        }
    </style>

</head>

<body>

<div class="container">

    <h2>Products Dashboard</h2>

    <!-- ANALYTICS -->
    <div class="stats">

        <div class="stat-card">
            <h3>{{ $totalProducts }}</h3>
            <p>Total Products</p>
        </div>

        <div class="stat-card">
            <h3>{{ $activeProducts }}</h3>
            <p>Active Products</p>
        </div>

        <div class="stat-card">
            <h3>{{ $totalManagers }}</h3>
            <p>Total Managers</p>
        </div>

    </div>

    <!-- TOP BAR -->
    <div class="top-bar">

        <a href="{{ route('products.create') }}" class="btn">➕ Create Product</a>

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

            <span class="badge creator">Creator: {{ $product->managers->creator?->name ?? 'N/A' }}</span>
            <span class="badge updater">Updater: {{ $product->managers->updater?->name ?? 'N/A' }}</span>
            <span class="badge deleter">Deleter: {{ $product->managers->deleter?->name ?? 'N/A' }}</span>
            <span class="badge status">Status: {{ $product->status }}</span>

            <div class="actions">
                <a href="{{ route('products.edit',$product->id) }}" class="edit-btn">Edit</a>

                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="delete-btn" onclick="return confirm('Delete this product?')">
                        Delete
                    </button>
                </form>
            </div>

        </div>

        @endforeach

    </div>

    <!-- CUSTOM PAGINATION (ONLY NUMBERS + < >) -->
    <div class="pagination-wrapper">

        <div class="pagination">

            {{-- PREVIOUS --}}
            @if ($products->onFirstPage())
                <span class="disabled">&lt;</span>
            @else
                <a href="{{ $products->previousPageUrl() }}">&lt;</a>
            @endif

            {{-- PAGE NUMBERS --}}
            @for ($i = 1; $i <= $products->lastPage(); $i++)
                @if ($i == $products->currentPage())
                    <span class="active">{{ $i }}</span>
                @else
                    <a href="{{ $products->url($i) }}">{{ $i }}</a>
                @endif
            @endfor

            {{-- NEXT --}}
            @if ($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}">&gt;</a>
            @else
                <span class="disabled">&gt;</span>
            @endif

        </div>

    </div>

</div>

</body>
</html>