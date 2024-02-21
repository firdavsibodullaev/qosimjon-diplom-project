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
        return User::query()->with('factoryFloor.factoryRelation')->orderBy('id')->paginate(20);
    }

    public function create(UserPayloadDTO $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $user = new User([
                'name' => $payload->name,
                'username' => $payload->username,
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
                'name' => $payload->name,
                'username' => $payload->username,
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
