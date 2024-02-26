<?php

namespace App\Services;

use App\DTOs\User\FilterDTO;
use App\DTOs\User\UserPayloadDTO;
use App\Enums\Role\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(protected FactoryFloorService $factoryFloorService)
    {
    }

    public function paginate(FilterDTO $filter): LengthAwarePaginator
    {
        return User::filter($filter)
            ->with([
                'factoryFloors' => fn(BelongsToMany $belongsToMany) => $belongsToMany->with(
                    relations: 'factoryRelation',
                    callback: fn(BelongsTo $belongsTo) => $belongsTo->withTrashed()
                )->withTrashed(),
                'roles'
            ])
            ->orderBy('id')
            ->paginate($filter->total);
    }

    public function create(UserPayloadDTO $payload): User
    {
        dd($payload);
        return DB::transaction(function () use ($payload) {
            $user = new User([
                'last_name' => $payload->last_name,
                'first_name' => $payload->first_name,
                'username' => $payload->username,
                'password' => bcrypt($payload->password)
            ]);

            $user->save();

            if (!$payload->role->is(Role::ADMIN)) {
                $user->factoryFloors()->sync($this->getFloorIds($payload->factory_id, $payload->factory_floor_id));
            }

            $user->syncRoles($payload->role->value);

            return $user;
        });
    }

    public function update(User $user, UserPayloadDTO $payload): User
    {
        return DB::transaction(function () use ($user, $payload) {
            $user->fill([
                'last_name' => $payload->last_name,
                'first_name' => $payload->first_name,
                'username' => $payload->username,
                'password' => $payload->password ? bcrypt($payload->password) : $user->password
            ]);

            $user->save();

            if (!$payload->role->is(Role::ADMIN)) {
                $user->factoryFloors()->sync($this->getFloorIds($payload->factory_id, $payload->factory_floor_id));
            }

            $user->syncRoles($payload->role->value);

            return $user;
        });
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    protected function getFloorIds(int $factory_id, array $factory_floor_id): array
    {
        if (count($factory_floor_id)) {
            return $factory_floor_id;
        }

        return $this->factoryFloorService->getByFactoryId($factory_id)->pluck('id')->toArray();
    }
}
