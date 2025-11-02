<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Screen\AsSource;

class Tariff extends Model
{
    use AsSource, Filterable, HasFactory;

    protected $table = 'tariffs';

    protected $fillable = [
        'name',
        'speed',
        'price',
        'is_active',
    ];

    protected $casts = [
        'speed' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $allowedFilters = [
        'id' => Where::class,
        'name' => Like::class,
        'speed' => Where::class,
        'price' => Where::class,
        'is_active' => Where::class,
    ];

    protected $allowedSorts = [
        'id',
        'name',
        'speed_mbps',
        'price',
        'is_active',
        'updated_at',
        'created_at',
    ];

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }
}
