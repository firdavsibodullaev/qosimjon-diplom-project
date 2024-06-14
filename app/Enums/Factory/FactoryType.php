<?php

namespace App\Enums\Factory;

enum FactoryType: string
{
    case TESTER = 'tester';
    case GIVING_FOR_TEST = 'giving_for_test';

    public function is(self $type): bool
    {
        return $this === $type;
    }
}
