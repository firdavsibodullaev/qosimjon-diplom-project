<?php

namespace App\Observers;

use App\Models\FactoryFloor;

class FactoryFloorObserver
{
    public function forceDeleting(FactoryFloor $factoryFloor): void
    {
        $factoryFloor->users->each->forceDelete();
    }
}
