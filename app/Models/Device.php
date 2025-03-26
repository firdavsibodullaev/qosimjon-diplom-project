<?php

namespace App\Models;

use App\Filters\Device\FilterName;
use App\Filters\Sorter;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<AttributeDevice> $attributes
 * @property-read Collection $factory
 * @property-read Collection $factoryFloor
 */
class Device extends Model
{
    use HasFactory, SoftDeletes, InteractsWithFilters;

    protected $fillable = ['name'];

    protected array $filters = [
        Sorter::class => null,
        FilterName::class => null,
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(AttributeDevice::class, 'device_id')->with(['value.attribute']);
    }

    public function factoryFloor(): BelongsToMany
    {
        return $this->belongsToMany(
            related: FactoryFloor::class,
            table: 'factory_device'
        )
            ->withPivot([
                'number',
                'full_number',
                'status',
                'position',
            ])
            ->using(FactoryDevice::class);
    }

    public function factoryRelation(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Factory::class,
            table: 'factory_device'
        )
            ->withPivot([
                'number',
                'full_number',
                'status',
                'position',
            ])
            ->using(FactoryDevice::class);
    }
}
