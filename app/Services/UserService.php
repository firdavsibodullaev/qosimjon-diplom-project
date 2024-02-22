<?php

namespace App\Services;

use App\DTOs\User\UserPayloadDTO;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function paginate(): LengthAwarePaginator
    {
        return User::query()
            ->with('factoryFloor.factoryRelation', 'roles')
            ->orderBy('id')
            ->paginate(20);
    }

    public function create(UserPayloadDTO $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $user = new User([
                'last_name' => $payload->last_name,
                'first_name' => $payload->last_name,
                'username' => $payload->username,
                'factory_floor_id' => $payload->factory_floor_id,
                'password' => bcrypt($payload->password)
            ]);

            $user->save();

            $user->syncRoles($payload->role->value);

            return $user;
        });
    }

    public function update(User $user, UserPayloadDTO $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $user->fill([
                'last_name' => $payload->last_name,
                'first_name' => $payload->last_name,
                'username' => $payload->username,
                'factory_floor_id' => $payload->factory_floor_id,
                'password' => $payload->password ? bcrypt($payload->password) : $user->password
            ]);

            $user->save();

            $user->syncRoles($payload->role->value);

            return $user;
        });
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
