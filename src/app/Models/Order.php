<?php

namespace App\Models;

use App\Orchid\Filters\ClientFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Screen\AsSource;

class Order extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'client_name',
        'client_phone',
        'address_full',
        'status',
        'connection_time',
        'location_id',
        'tariff_id',
        'operator_id',
        'brigade_id',
    ];

    protected $casts = [
        'connection_time' => 'datetime',
    ];

    protected $allowedFilters = [
        'id' => Where::class,
        'client' => ClientFilter::class,
        'client_name' => Like::class,
        'client_phone' => Like::class,
        'address_full' => Like::class,
        'status' => Where::class,
        'connection_time' => WhereDateStartEnd::class,
        'location_id' => Where::class,
        'tariff_id' => Where::class,
        'operator_id' => Where::class,
        'brigade_id' => Where::class,
        'created_at' => WhereDateStartEnd::class,
        'updated_at' => WhereDateStartEnd::class,
    ];

    protected $allowedSorts = [
        'id',
        'client_name',
        'client_phone',
        'status',
        'connection_time',
        'location_id',
        'tariff_id',
        'operator_id',
        'brigade_id',
        'updated_at',
        'created_at',
    ];

    /**
     * Связь с локацией (поселком)
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function brigade(): BelongsTo
    {
        return $this->belongsTo(User::class, 'brigade_id');
    }

    public function getStatusEnum(): ?OrderStatus
    {
        return OrderStatus::tryFrom($this->status);
    }

    public function getConnectionTimeFormatted(): ?string
    {
        return $this->connection_time?->format('d.m.Y H:i');
    }

    public function getCreatedAtFormatted(): ?string
    {
        return $this->created_at?->format('d.m.Y H:i');
    }

    public function getUpdatedAtFormatted(): ?string
    {
        return $this->updated_at?->format('d.m.Y H:i');
    }
}
