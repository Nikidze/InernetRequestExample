<?php

namespace App\Orchid\Screens\Location;

use App\Models\Location;
use App\Orchid\Layouts\Location\LocationListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Tabuna\Breadcrumbs\Trail;

class LocationListScreen extends Screen
{
    public function query(): array
    {
        return [
            'locations' => Location::filters()->defaultSort('name')->paginate(20),
        ];
    }

    public function name(): ?string
    {
        return 'Локации';
    }

    public function description(): ?string
    {
        return 'Управление населенными пунктами';
    }

    public function commandBar(): array
    {
        return [
            Link::make(__('Добавить локацию'))
                ->icon('bs.plus-circle')
                ->route('platform.locations.create')
                ->canSee(auth()->user()->hasAccess('platform.locations.create')),
        ];
    }

    public function layout(): array
    {
        return [
            LocationListLayout::class,
        ];
    }

    public function permission(): array
    {
        return ['platform.locations'];
    }

    public function breadcrumb(): array
    {
        return [
            Trail::make(__('Главная'))->route(config('platform.index')),
            Trail::make('Локации'),
        ];
    }
}
