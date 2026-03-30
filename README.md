# PHP_Laravel12_Extended_Relationship

A Laravel 12 app demonstrating multiple foreign key relationships using the Laravel Extended Relationships package. Tracks creators, updaters, and deleters for products with a dashboard and forms.


## Project Description:

PHP_Laravel12_Extended_Relationship is a modern Laravel 12 web application that demonstrates how to manage multiple foreign key relationships in a clean and maintainable way using the Laravel Extended Relationships package.

This project is ideal for developers who want to track who created, updated, and deleted a record in a table without writing repetitive code or complex query logic. 

It provides a dashboard to view all products along with their related managers (creator, updater, deleter) and a form to add new products while linking managers to specific actions.



## Features:

- Track created_by, updated_by, deleted_by for products
- Modern dashboard showing products and related managers
- Create product form with manager selection
- Uses HasExtendedRelationships for clean Eloquent relationships
- Server-side validation and user-friendly error messages



## Technology Stack:

- Backend: Laravel 12, PHP 8+
- Database: MySQL
- Frontend: Blade templates, custom CSS, Google Fonts
- Packages: mr-punyapal/laravel-extended-relationships



## Usage:

- Create managers via Tinker
- Add products linking managers to actions
- View dashboard to see relationships


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel:"12.*" PHP_Laravel12_Extended_Relationship

```

### Go inside project:

```
cd PHP_Laravel12_Extended_Relationship

```

#### Explanation:

Installs a fresh Laravel 12 application and sets up the project directory.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_extended_relationship
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_extended_relationship

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Configures MySQL database connection and runs default migrations to create system tables.





## STEP 3: Install Laravel Extended Relationships Package 

### Run:

```
composer require mr-punyapal/laravel-extended-relationships

```

#### Explanation:

Adds the package that allows defining multiple foreign key relationships on a single model easily.





## STEP 4: Create Models + Migrations

### Run:

```
php artisan make:model Product -m

php artisan make:model Manager -m

```

#### Explanation:

Generates Product and Manager models along with migration files to define their database tables.




## STEP 5: Define Migrations

### database/migrations/xxxx_create_managers_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('managers');
    }
};

```




### database/migrations/xxxx_create_products_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

```


### Then Run:

```
php artisan migrate

```

#### Explanation:

Sets up the database structure for managers and products tables including foreign key columns.




## STEP 6: Add Extended Relationships Trait

### app/Models/Product.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MrPunyapal\LaravelExtendedRelationships\HasExtendedRelationships;

class Product extends Model
{
    use HasExtendedRelationships;

    protected $fillable = ['name','description','created_by','updated_by','deleted_by'];

    // Extended relationship for multiple foreign keys
    public function managers()
    {
        return $this->belongsToManyKeys(
            related: Manager::class,
            foreignKey: 'id',
            relations: [
                'created_by' => 'creator',
                'updated_by' => 'updater',
                'deleted_by' => 'deleter',
            ]
        );
    }
}

```


### app/Models/Manager.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MrPunyapal\LaravelExtendedRelationships\HasExtendedRelationships;

class Manager extends Model
{
    use HasExtendedRelationships;

    protected $fillable = ['name'];

    // Reverse relation
    public function auditedProducts()
    {
        return $this->hasManyKeys(
            related: Product::class,
            relations: [
                'created_by' => 'created',
                'updated_by' => 'updated',
                'deleted_by' => 'deleted',
            ],
            localKey: 'id'
        );
    }
}

```

#### Explanation:

Implements HasExtendedRelationships trait to manage multiple foreign keys and define clean relationships.





## STEP 7: Add Managers Manually

### Open Tinker:

```
php artisan tinker 

```

### Then create managers:

```
use App\Models\Manager;

// Create 3 managers manually
$m1 = Manager::create(['name' => 'Alice']);
$m2 = Manager::create(['name' => 'Bob']);
$m3 = Manager::create(['name' => 'Charlie']);

```


### Still in Tinker:

```
use App\Models\Product;

// Create a product and link to managers
Product::create([
    'name' => 'Product A',
    'description' => 'First Product',
    'created_by' => $m1->id,
    'updated_by' => $m2->id,
    'deleted_by' => $m3->id
]);

Product::create([
    'name' => 'Product B',
    'description' => 'Second Product',
    'created_by' => $m2->id,
    'updated_by' => $m3->id,
    'deleted_by' => $m1->id
]);

```


#### Explanation:

Uses Tinker to create sample manager records and products to test the extended relationships.





## STEP 8: Create Controller

### Run:

```
php artisan make:controller ProductController

```

### app/Http/Controllers/ProductController.php:

```
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Manager;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('managers')->get();
        return view('products.index', compact('products'));
    }

    // Show create form
    public function create()
    {
        $managers = Manager::all(); // list of managers for dropdown
        return view('products.create', compact('managers'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'nullable|exists:managers,id',
            'updated_by' => 'nullable|exists:managers,id',
            'deleted_by' => 'nullable|exists:managers,id',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }
}

```

#### Explanation:

Handles logic for listing, creating, and storing products, including validation and relationship loading.




## STEP 9: Define Route

### routes/web.php:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// List Products - name fixed
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Create Product form
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Store Product
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');

```

#### Explanation:

Sets up web routes for displaying the dashboard, creating products, and storing products in the database.




## STEP 10: Create Blade View

### resources/views/products/index.blade.php

```
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

```



### resources/views/products/create.blade.php:

```
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

```



#### Explanation:

Creates beautiful dashboard and form views using Blade templates to display products and related managers.




## STEP 11: Run Application  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts Laravel’s local development server and allows testing the application in a browser.




## Expected Output:

### Products Dashboard:


<img src="screenshots/Screenshot 2026-03-30 160003.png" width="900">


### Add New Product:


<img src="screenshots/Screenshot 2026-03-30 160128.png" width="900">


### Product Created Successfully:


<img src="screenshots/Screenshot 2026-03-30 160153.png" width="900">



---

## Project Folder Structure:

```
PHP_Laravel12_Extended_Relationship/
│
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ProductController.php          <-- Your ProductController
│   │   ├── Middleware/
│   │   └── Kernel.php
│   ├── Models/
│   │   ├── Product.php                        <-- Product Model with HasExtendedRelationships
│   │   └── Manager.php                        <-- Manager Model with HasExtendedRelationships
│   ├── Providers/
│   └── ...
│
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   │   ├── xxxx_create_managers_table.php     <-- Managers migration
│   │   ├── xxxx_create_products_table.php     <-- Products migration
│   │   └── ...
│   └── seeders/
│
├── public/
│   ├── index.php
│   └── ...
│
├── resources/
│   ├── views/
│   │   ├── products/
│   │   │   ├── index.blade.php               <-- Products Dashboard view
│   │   │   └── create.blade.php              <-- Create Product form view
│   │   └── welcome.blade.php
│   ├── css/                                   <-- Optional if you separate CSS
│   └── js/                                    <-- Optional JS
│
├── routes/
│   └── web.php                                <-- Your routes for products
│
├── storage/
├── tests/
├── vendor/
├── .env                                       <-- Database & environment configs
├── composer.json
├── package.json
└── artisan

```
