<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $device_id
 * @property int $attribute_id
 * @property int $attribute_value_id
 * @property string $measurement_unit
 * @property-read Device $device
 * @property-read Attribute $attribute
 * @property-read AttributeValue $value
 */
class AttributeDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'attribute_id',
        'attribute_value_id',
        'measurement_unit'
    ];

    public $timestamps = false;

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
