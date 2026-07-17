<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'color', 'category'];

    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function managers()
    {
        return $this->morphedByMany(Manager::class, 'taggable');
    }
}
