<?php

namespace App\Orchid\Layouts\Location;

use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Layouts\Rows;

class LocationEditLayout extends Rows
{
    public function fields(): array
    {
        return [
            Input::make('location.name')
                ->title('Название локации')
                ->placeholder('Введите название населенного пункта')
                ->required()
                ->maxLength(255),

            Switcher::make('location.is_active')
                ->sendTrueOrFalse()
                ->title('Активная')
                ->help('Можно ли подключать интернет в этом населенном пункте'),
        ];
    }
}
