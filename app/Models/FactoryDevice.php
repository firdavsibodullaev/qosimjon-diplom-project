<?php

namespace App\Models;

use App\Enums\FactoryDevice\Position;
use App\Enums\FactoryDevice\Status;
use App\Filters\FactoryDevice\UserFilter;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property int $factory_id
 * @property int $factory_floor_id
 * @property int $device_id
 * @property int $number
 * @property string $full_number
 * @property Status $status
 * @property Position $position
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Factory $factory
 * @property-read FactoryFloor|null $factoryFloor
 * @property-read Device $device
 */
class FactoryDevice extends Model
{
    use HasFactory, InteractsWithFilters;

    public array $filters = [
        UserFilter::class => null
    ];

    protected $table = 'factory_device';

    protected $fillable = [
        'factory_id',
        'factory_floor_id',
        'device_id',
        'number',
        'full_number',
        'status',
        'position',
    ];

    protected $casts = [
        'status' => Status::class,
        'position' => Position::class,
    ];

    public function factory(): BelongsTo
    {
        return $this->belongsTo(Factory::class);
    }

    public function factoryFloor(): BelongsTo
    {
        return $this->belongsTo(FactoryFloor::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
