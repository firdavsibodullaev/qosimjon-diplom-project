<?php

namespace App\Models;

use App\Filters\Sorter;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $name
 * @property string|null $measurement_unit
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<AttributeValue> $values
 */
class Attribute extends Model
{
    use HasFactory, InteractsWithFilters;

    protected $fillable = ['name', 'measurement_unit'];

    protected array $filters = [
        Sorter::class => null
    ];

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}
