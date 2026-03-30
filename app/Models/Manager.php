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
