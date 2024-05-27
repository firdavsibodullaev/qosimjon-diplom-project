<?php

namespace App\Models;

use App\Enums\Factory\FactoryType;
use App\Filters\Factory\Permitted;
use App\Filters\Factory\TypeFilter;
use App\Filters\Sorter;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property string $name
 * @property int $number
 * @property FactoryType $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<FactoryFloor> $factoryFloors
 */
class Factory extends Model
{
    use HasFactory, SoftDeletes, InteractsWithFilters;

    protected $fillable = [
        'name',
        'number',
        'type'
    ];

    protected array $filters = [
        Sorter::class => null,
        TypeFilter::class => 'type',
        Permitted::class => null,
    ];

    protected $casts = [
        'type' => FactoryType::class
    ];

    public function factoryFloors(): HasMany
    {
        return $this->hasMany(FactoryFloor::class);
    }
}
