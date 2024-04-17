<?php

namespace App\Models;

use App\Filters\Sorter;
use App\Filters\User\Permitted;
use App\Filters\User\WithTrashedByFactoryFloor;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read int $id
 * @property string $last_name
 * @property string $first_name
 * @property string $username
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $name
 * @property-read Collection $factoryFloors
 * @property-read bool $has_permission_to_all_floors
 */
class User extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, InteractsWithFilters;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'username',
        'password',
    ];

    protected array $filters = [
        Permitted::class => null,
        Sorter::class => null,
        WithTrashedByFactoryFloor::class => null
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function factoryFloors(): BelongsToMany
    {
        return $this->belongsToMany(FactoryFloor::class);
    }

    public function name(): Attribute
    {
        return Attribute::get(fn() => "$this->last_name $this->first_name");
    }

    public function hasPermissionToAllFloors(): Attribute
    {
        $this->loadMissing('factoryFloors');

        $factory_ids = $this->factoryFloors->pluck('factory_id')->unique();

        $floors_count = FactoryFloor::query()->whereIn('factory_id', $factory_ids)->count();

        return Attribute::get(fn() => $floors_count === $this->factoryFloors->count());
    }
}
