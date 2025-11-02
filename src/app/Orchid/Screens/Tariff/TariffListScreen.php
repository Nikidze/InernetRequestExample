<?php

namespace App\Orchid\Screens\Tariff;

use App\Models\Tariff;
use App\Orchid\Layouts\Tariff\TariffListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Tabuna\Breadcrumbs\Trail;

class TariffListScreen extends Screen
{
    public function query(): array
    {
        return [
            'tariffs' => Tariff::filters()->defaultSort('name')->paginate(20),
        ];
    }

    public function name(): ?string
    {
        return 'Тарифы';
    }

    public function description(): ?string
    {
        return 'Управление тарифными планами';
    }

    public function commandBar(): array
    {
        return [
            Link::make(__('Добавить тариф'))
                ->icon('bs.plus-circle')
                ->route('platform.tariffs.create')
                ->canSee(auth()->user()->hasAccess('platform.tariffs.create')),
        ];
    }

    public function layout(): array
    {
        return [
            TariffListLayout::class,
        ];
    }

    public function permission(): array
    {
        return ['platform.tariffs'];
    }

    public function breadcrumb(): array
    {
        return [
            Trail::make(__('Главная'))->route(config('platform.index')),
            Trail::make('Тарифы'),
        ];
    }
}
