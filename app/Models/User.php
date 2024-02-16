<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read int $id
 * @property string $last_name
 * @property string $first_name
 * @property string $username
 * @property string $password
 * @property int $factory_floor_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read string $name
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'username',
        'password',
        'factory_floor_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function factoryFloor(): BelongsTo
    {
        return $this->belongsTo(FactoryFloor::class);
    }

    public function name(): Attribute
    {
        return Attribute::get(fn() => "$this->last_name $this->first_name");
    }
}
