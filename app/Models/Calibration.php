<?php

namespace App\Models;

use App\Enums\Calibration\Result;
use App\Enums\Calibration\Status;
use App\Filters\Calibration\FilterSearch;
use App\Filters\Sorter;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read int $id
 * @property int $factory_device_id
 * @property int $applicant_factory_id
 * @property int $applicant_id
 * @property int $checker_factory_id
 * @property int $checker_id
 * @property Carbon $checked_at
 * @property Status $status
 * @property Result $result
 * @property string $comment
 * @property Carbon $deadline
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $applicant
 * @property-read Factory $applicantFactory
 * @property-read User $checker
 * @property-read Factory $checkerFactory
 * @property-read FactoryDevice $device
 */
class Calibration extends Model implements HasMedia
{
    use HasFactory, InteractsWithFilters, InteractsWithMedia;

    public array $filters = [
        Sorter::class => null,
        FilterSearch::class => null
    ];

    protected $fillable = [
        'factory_device_id',
        'applicant_factory_id',
        'applicant_id',
        'checker_factory_id',
        'checker_id',
        'checked_at',
        'status',
        'result',
        'comment',
        'deadline',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'status' => Status::class,
        'result' => Result::class,
        'deadline' => 'datetime'
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function applicantFactory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'applicant_factory_id');
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    public function checkerFactory(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'checker_factory_id');
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(FactoryDevice::class, 'factory_device_id');
    }
}
