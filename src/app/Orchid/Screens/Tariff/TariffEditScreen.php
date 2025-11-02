<?php

namespace App\Orchid\Screens\Tariff;

use App\Models\Tariff;
use App\Orchid\Layouts\Tariff\TariffEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class TariffEditScreen extends Screen
{
    public ?int $tariffId = null;

    public function query(Tariff $tariff): array
    {
        $this->tariffId = $tariff->exists ? $tariff->id : null;

        return [
            'tariff' => $tariff,
        ];
    }

    public function name(): ?string
    {
        return $this->tariffId ? 'Редактировать тариф' : 'Создать тариф';
    }

    public function description(): ?string
    {
        return $this->tariffId ? 'Изменение параметров тарифа' : 'Добавление нового тарифа';
    }

    public function commandBar(): array
    {
        return [
            Button::make(__('Сохранить'))
                ->icon('bs.check-circle')
                ->method('save'),

            Button::make(__('Удалить'))
                ->icon('bs.trash3')
                ->method('remove')
                ->canSee($this->tariffId !== null)
                ->confirm(__('Вы уверены, что хотите удалить этот тариф?')),
        ];
    }

    public function layout(): array
    {
        return [
            TariffEditLayout::class,
        ];
    }

    public function permission(): array
    {
        return [$this->tariffId ? 'platform.tariffs.edit' : 'platform.tariffs.create'];
    }

    public function save(Request $request, Tariff $tariff): RedirectResponse
    {
        $request->validate([
            'tariff.name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tariffs', 'name')->ignore($this->tariffId),
            ],
            'tariff.speed' => 'required|integer|min:1|max:10000',
            'tariff.price' => 'required|numeric|min:0',
            'tariff.is_active' => 'boolean',
        ]);

        $tariff->fill($request->input('tariff'))
            ->save();

        Alert::success('Тариф успешно сохранен!');
        return redirect()->route('platform.tariffs.list');
    }

    public function remove(Tariff $tariff): RedirectResponse
    {
        $tariff->delete();

        Alert::success('Тариф успешно удален!');
        return redirect()->route('platform.tariffs.list');
    }
}
