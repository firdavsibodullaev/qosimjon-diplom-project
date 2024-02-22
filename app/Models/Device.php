<?php

namespace App\Models;

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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<AttributeDevice> $attributes
 */
class Device extends Model
{
    use HasFactory, SoftDeletes, InteractsWithFilters;

    protected $fillable = ['name'];

    protected array $filters = [
        Sorter::class => null
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(AttributeDevice::class, 'device_id')->with(['value.attribute']);
    }
}
