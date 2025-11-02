<?php

namespace App\Orchid\Layouts\Location;

use App\Models\Location;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class LocationListLayout extends Table
{
    public $target = 'locations';

    public function columns(): array
    {
        return [
            TD::make('name', 'Название локации')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn (Location $location) => Link::make($location->name)
                    ->route('platform.locations.edit', $location)),

            TD::make('is_active', 'Статус')
                ->sort()
                ->render(fn (Location $location) => $location->is_active ? 'Активный' : 'Неактивный'),

            TD::make('orders_count', 'Заявок')
                ->sort()
                ->render(fn (Location $location) => $location->orders()->count()),

            TD::make('created_at', 'Создан')
                ->sort()
                ->render(fn (Location $location) => $location->created_at?->format('d.m.Y H:i')),

            TD::make('updated_at', 'Обновлен')
                ->sort()
                ->render(fn (Location $location) => $location->updated_at?->format('d.m.Y H:i')),
        ];
    }
}
