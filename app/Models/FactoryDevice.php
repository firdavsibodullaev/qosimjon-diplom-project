<?php

namespace App\Models;

use App\Enums\FactoryDevice\Position;
use App\Enums\FactoryDevice\Status;
use App\Filters\FactoryDevice\UserFilter;
use App\Filters\FactoryDevice\WithCalibrationFilter;
use App\Filters\FactoryDevice\WithSorterFilter;
use App\Filters\Sorter;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read int $id
 * @property int $factory_id
 * @property int $factory_floor_id
 * @property int $device_id
 * @property int $number
 * @property string $full_number
 * @property Status $status
 * @property Position $position
 * @property Carbon|null $last_checked_at
 * @property int|null $check_every_time
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
        UserFilter::class => null,
        Sorter::class => null,
        WithCalibrationFilter::class => null,
        WithSorterFilter::class => null,
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
        'last_checked_at',
        'check_every_time',
    ];

    protected $casts = [
        'status' => Status::class,
        'position' => Position::class,
        'last_checked_at' => 'datetime'
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

    public function lastCalibration(): HasOne
    {
        return $this->hasOne(Calibration::class, 'factory_device_id')->latest();
    }

    public function calibrations(): HasMany
    {
        return $this->hasMany(Calibration::class, 'factory_device_id')->latest();
    }
}
