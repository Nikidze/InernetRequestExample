<?php

namespace App\Orchid\Screens\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Orchid\Layouts\Order\OrderListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Tabuna\Breadcrumbs\Trail;

class OrderListScreen extends Screen
{
    public function query(): array
    {
        $user = auth()->user();

        $query = Order::with(['tariff', 'location', 'operator', 'brigade'])
            ->filters();

        if ($user->hasRole('brigade')) {
            $query
                ->where('status', OrderStatus::IN_PROGRESS->value)
                ->where('brigade_id', $user->id);
        }

        if ($user->hasRole('operator')) {
            $query
                ->where('status', OrderStatus::IN_PROGRESS->value)
                ->orWhere('status', OrderStatus::NEW->value);
        }

        return [
            'orders' => $query->defaultSort('created_at', 'desc')->paginate(20),
        ];
    }

    public function name(): ?string
    {
        $user = auth()->user();

        if ($user->hasRole('brigade')) {
            return 'Рабочие заявки';
        } else {
            return 'Заявки';
        }
    }

    public function description(): ?string
    {
        $user = auth()->user();

        if ($user->hasRole('brigade')) {
            return 'Заявки, назначенные для выполнения';
        } elseif ($user->hasRole('operator')) {
            return 'Управление заявками на подключение';
        } else {
            return 'Все заявки на подключение интернета';
        }
    }

    public function commandBar(): array
    {
        $user = auth()->user();
        $commandBar = [];

        if ($user->hasRole('director') || $user->hasRole('operator')) {
            $commandBar[] = Link::make(__('Создать заявку'))
                ->icon('bs.plus-circle')
                ->route('platform.orders.create')
                ->canSee(auth()->user()->hasAccess('platform.orders.create'));
        }

        return $commandBar;
    }

    public function layout(): array
    {
        return [
            OrderListLayout::class,
        ];
    }

    public function permission(): array
    {
        return ['platform.orders'];
    }

    public function breadcrumb(): array
    {
        return [
            Trail::make(__('Главная'))->route(config('platform.index')),
            Trail::make('Заявки'),
        ];
    }
}
