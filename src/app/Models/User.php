<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Types\Like;
use Orchid\Filters\Types\Where;
use Orchid\Filters\Types\WhereDateStartEnd;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ["name", "email", "password"];

    protected $hidden = ["password", "remember_token", "permissions"];

    protected $casts = [
        "permissions" => "array",
        "email_verified_at" => "datetime",
    ];

    protected $allowedFilters = [
        "id" => Where::class,
        "name" => Like::class,
        "email" => Like::class,
        "updated_at" => WhereDateStartEnd::class,
        "created_at" => WhereDateStartEnd::class,
    ];

    protected $allowedSorts = [
        "id",
        "name",
        "email",
        "updated_at",
        "created_at",
    ];

    /**
     * Связь с заявками, созданными пользователем как оператором
     */
    public function ordersAsOperator(): HasMany
    {
        return $this->hasMany(Order::class, "operator_id");
    }

    public function ordersAsBrigade(): HasMany
    {
        return $this->hasMany(Order::class, "brigade_id");
    }

    public function allOrders(): HasMany
    {
        return $this->hasMany(Order::class, "operator_id")->orWhere(
            "brigade_id",
            $this->id,
        );
    }

    /**
     * Проверка наличия роли у пользователя
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->where("slug", $role)->isNotEmpty();
        }

        return (bool) $this->roles->intersect($role)->count();
    }

    public function hasAccess(string $permit, bool $cache = true): bool
    {
        // Директор имеет все права
        if ($this->hasRole("director")) {
            return true;
        }

        // Проверяем права из базы данных
        $permissions = $this->permissions ?? [];

        return $permissions[$permit] ?? false;
    }

    #[Scope]
    protected function operator(Builder $query): void
    {
        $query->whereHas("roles", function ($q) {
            $q->where("slug", "operator");
        });
    }

    #[Scope]
    protected function brigade(Builder $query): void
    {
        $query->whereHas("roles", function ($q) {
            $q->where("slug", "brigade");
        });
    }
}
