<?php

namespace App\Orchid\Layouts\Order;

use App\Enums\OrderStatus;
use App\Models\Location;
use App\Models\Tariff;
use App\Models\User;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class OrderEditLayout extends Rows
{
    public function fields(): array
    {
        $user = auth()->user();

        $fields = [
            Input::make('order.client_name')
                ->title('Имя клиента')
                ->placeholder('Введите имя клиента')
                ->required()
                ->maxLength(255),

            Input::make('order.client_phone')
                ->title('Телефон клиента')
                ->placeholder('+79001234567')
                ->required()
                ->maxLength(20),

            TextArea::make('order.address_full')
                ->title('Адрес')
                ->placeholder('Полный адрес клиента')
                ->required()
                ->rows(3),

            Relation::make('order.location_id')
                ->fromModel(Location::class, 'name')
                ->applyScope('active')
                ->required()
                ->title('Выберите локацию'),

            Relation::make('order.tariff_id')
                ->fromModel(Tariff::class, 'name')
                ->applyScope('active')
                ->required()
                ->title('Выберите тариф'),

            DateTimer::make('order.connection_time')
                ->title('Время подключения')
                ->required()
                ->enableTime()
                ->format('Y-m-d H:i')
                ->format24hr()
                ->placeholder('Выберите дату и время'),
        ];

        if ($user->hasRole('director')) {
            $fields[] = Relation::make('order.operator_id')
                ->fromModel(User::class, 'name')
                ->applyScope('operator')
                ->required()
                ->title('Выберете оператора');
        }

        if ($user->hasRole('director') || $user->hasRole('operator')) {
            $fields[] = Select::make('order.status')
                ->title('Статус')
                ->options(collect(OrderStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()]))
                ->required();
        } elseif ($user->hasRole('brigade')) {
            $fields[] = Select::make('order.status')
                ->title('Статус')
                ->options([
                    OrderStatus::IN_PROGRESS->value => OrderStatus::IN_PROGRESS->label(),
                    OrderStatus::COMPLETED->value => OrderStatus::COMPLETED->label(),
                    OrderStatus::CANCELED->value => OrderStatus::CANCELED->label(),
                ])
                ->required();
        }

        if ($user->hasRole('director') || $user->hasRole('operator')) {
            $fields[] = Relation::make('order.brigade_id')
                ->fromModel(User::class, 'name')
                ->applyScope('brigade')
                ->title('Выберете бригаду');
        }

        return $fields;
    }
}
