<?php

namespace App\Models;

use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $value
 * @property int $attribute_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Attribute $attribute
 * @property-read Collection<AttributeDevice> $devices
 */
class AttributeValue extends Model
{
    use HasFactory, InteractsWithFilters;

    protected $fillable = [
        'value', 'attribute_id'
    ];

    protected array $filters = [];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(AttributeDevice::class, 'attribute_value_id');
    }
}
