<?php

namespace App\Observers;

use App\Models\Factory;

class FactoryObserver
{
    public function forceDeleting(Factory $factory): void
    {
        $factory->factoryFloors->each->forceDelete();
    }
}
