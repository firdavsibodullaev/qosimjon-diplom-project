<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $user_id
 * @property int $factory_floor_id
 */
class FactoryFloorUser extends Pivot
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'factory_floor_id'
    ];
}
