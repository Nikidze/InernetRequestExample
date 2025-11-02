<?php

namespace App\Orchid\Layouts\Tariff;

use App\Models\Tariff;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class TariffListLayout extends Table
{
    public $target = 'tariffs';

    public function columns(): array
    {
        return [
            TD::make('name', 'Название')
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(fn (Tariff $tariff) => Link::make($tariff->name)
                    ->route('platform.tariffs.edit', $tariff)),

            TD::make('speed', 'Скорость (Мбит/с)')
                ->sort()
                ->render(fn (Tariff $tariff) => $tariff->speed.' Мбит/с'),

            TD::make('price', 'Цена (руб.)')
                ->sort()
                ->render(fn (Tariff $tariff) => number_format($tariff->price, 0, '.', ' ').' руб.'),

            TD::make('is_active', 'Статус')
                ->sort()
                ->render(fn (Tariff $tariff) => $tariff->is_active ? 'Активный' : 'Неактивный'),

            TD::make('created_at', 'Создан')
                ->sort()
                ->render(fn (Tariff $tariff) => $tariff->created_at?->format('d.m.Y H:i')),

            TD::make('updated_at', 'Обновлен')
                ->sort()
                ->render(fn (Tariff $tariff) => $tariff->updated_at?->format('d.m.Y H:i')),
        ];
    }
}
