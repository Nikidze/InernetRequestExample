<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Главная')
                ->icon('bs.house')
                ->title('Навигация')
                ->route(config('platform.index')),

            Menu::make('Заявки')
                ->icon('bs.list-task')
                ->route('platform.orders.list')
                ->permission('platform.orders'),

            Menu::make('Тарифы')
                ->icon('bs.currency-dollar')
                ->route('platform.tariffs.list')
                ->permission('platform.tariffs'),

            Menu::make('Локации')
                ->icon('bs.geo-alt')
                ->route('platform.locations.list')
                ->permission('platform.locations')
                ->divider(),

            Menu::make(__('Пользователи'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Система')),

            Menu::make(__('Роли'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('Система'))
                ->addPermission('platform.systems.roles', __('Роли'))
                ->addPermission('platform.systems.users', __('Пользователи')),

            ItemPermission::group(__('Управление тарифами'))
                ->addPermission('platform.tariffs', __('Тарифы'))
                ->addPermission('platform.tariffs.create', __('Создание тарифов'))
                ->addPermission('platform.tariffs.edit', __('Редактирование тарифов'))
                ->addPermission('platform.tariffs.delete', __('Удаление тарифов')),

            ItemPermission::group(__('Управление локациями'))
                ->addPermission('platform.locations', __('Локации'))
                ->addPermission('platform.locations.create', __('Создание локаций'))
                ->addPermission('platform.locations.edit', __('Редактирование локаций'))
                ->addPermission('platform.locations.delete', __('Удаление локаций')),

            ItemPermission::group(__('Управление заявками'))
                ->addPermission('platform.orders', __('Заявки'))
                ->addPermission('platform.orders.view_all', __('Просмотр всех заявок'))
                ->addPermission('platform.orders.view_assigned', __('Просмотр назначенных заявок'))
                ->addPermission('platform.orders.create', __('Создание заявок'))
                ->addPermission('platform.orders.edit', __('Редактирование заявок'))
                ->addPermission('platform.orders.update_status', __('Изменение статуса заявок'))
                ->addPermission('platform.orders.complete', __('Завершение заявок')),
        ];
    }
}
