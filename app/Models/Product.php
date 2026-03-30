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
