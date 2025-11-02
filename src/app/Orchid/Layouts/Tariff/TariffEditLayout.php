<?php

namespace App\Orchid\Layouts\Tariff;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Rows;

class TariffEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('tariff.name')
                ->title('Название тарифа')
                ->placeholder('Введите название тарифа')
                ->required()
                ->maxLength(255),

            Input::make('tariff.speed')
                ->title('Скорость интернета')
                ->type('number')
                ->min(0)
                ->required()
                ->title('Скорость (Мбит/с)'),

            Input::make('tariff.price')
                ->title('Цена в месяц')
                ->type('number')
                ->min(0)
                ->step('0.01')
                ->placeholder('0.00')
                ->required()
                ->help('Укажите стоимость тарифа в рублях в месяц'),

            Switcher::make('tariff.is_active')
                ->sendTrueOrFalse()
                ->title('Активный')
                ->help('Отображать ли тариф в списке для выбора'),

        ];
    }
}
