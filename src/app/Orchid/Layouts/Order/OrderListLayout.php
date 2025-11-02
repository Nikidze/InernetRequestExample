<?php

namespace App\Orchid\Layouts\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Location;
use App\Orchid\Filters\ClientFilter;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class OrderListLayout extends Table
{
    public $target = "orders";

    public function styles(): array
    {
        return [
            "table" => "compact-order-table",
        ];
    }

    public function columns(): array
    {
        $user = auth()->user();

        $columns = [
            TD::make("client", "Клиент")
                ->sort("client_name")
                ->filter(ClientFilter::class)
                ->width("220px")
                ->render(
                    fn(Order $order) => view("components.order-client-cell", [
                        "order" => $order,
                    ]),
                ),

            TD::make("location_id", "Локация")
                ->width("140px")
                ->filter(
                    Relation::make('location_id')
                        ->fromModel(Location::class, 'name')
                        ->applyScope('active')
                        ->required()
                        ->title('Выберите локацию'),
                )
                ->render(
                    fn(Order $order) => $order->location->name ?? "Не указана",
                ),

            TD::make("address_full", "Адрес")
                ->width("200px")
                ->render(fn(Order $order) => $order->address_full),

            TD::make("status", "Статус")
                ->sort()
                ->width("120px")
                ->render(function (Order $order) {
                    $statusEnum = OrderStatus::tryFrom($order->status);
                    $statusLabel = $statusEnum?->label() ?? "Неизвестно";
                    $statusClass = match ($statusEnum) {
                        OrderStatus::NEW => "status-new",
                        OrderStatus::IN_PROGRESS => "status-in-progress",
                        OrderStatus::COMPLETED => "status-completed",
                        OrderStatus::CANCELED => "status-canceled",
                        default => "status-new",
                    };

                    return "<span class='status-badge {$statusClass}'>{$statusLabel}</span>";
                }),

            TD::make("tariff.name", "Тариф")
                ->width("140px")
                ->render(
                    fn(Order $order) => $order->tariff->name ?? "Не указан",
                ),
        ];

        if ($user->hasRole("director") || $user->hasRole("operator")) {
            $columns[] = TD::make("operator.name", "Оператор")
                ->width("140px")
                ->render(
                    fn(Order $order) => $order->operator->name ?? "Не назначен",
                );
        }

        if ($user->hasRole("director") || $user->hasRole("operator")) {
            $columns[] = TD::make("brigade.name", "Бригада")
                ->width("140px")
                ->render(
                    fn(Order $order) => $order->brigade->name ?? "Не назначена",
                );
        }

        $columns[] = TD::make("connection_time", "Время подкл.")
            ->width("140px")
            ->sort()
            ->render(fn(Order $order) => $order->getConnectionTimeFormatted());

        $columns[] = TD::make("created_at", "Создана")
            ->width("140px")
            ->sort()
            ->render(fn(Order $order) => $order->getCreatedAtFormatted());

        return $columns;
    }
}
