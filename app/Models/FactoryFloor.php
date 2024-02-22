<?php

namespace App\Models;

use App\Filters\FactoryFloor\FilterByFactoryId;
use App\Filters\Sorter;
use App\Traits\InteractsWithFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read int $id
 * @property string $name
 * @property int $number
 * @property int $factory_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<User> $users
 * @property-read Factory $factoryRelation
 */
class FactoryFloor extends Model
{
    use HasFactory, SoftDeletes, InteractsWithFilters;

    protected $fillable = [
        'name',
        'number',
        'factory_id'
    ];

    protected array $filters = [
        Sorter::class => null,
        FilterByFactoryId::class => 'factory_id'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function factoryRelation(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }
}
