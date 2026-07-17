<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MrPunyapal\LaravelExtendedRelationships\HasExtendedRelationships;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Manager extends Model
{
    use HasExtendedRelationships;

    protected $fillable = ['name'];

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

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
