<?php

namespace App\Orchid\Screens\Location;

use App\Models\Location;
use App\Orchid\Layouts\Location\LocationEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Tabuna\Breadcrumbs\Trail;

class LocationEditScreen extends Screen
{
    public ?int $locationId = null;

    public function query(Location $location): array
    {
        $this->locationId = $location->exists ? $location->id : null;

        return [
            'location' => $location,
        ];
    }

    public function name(): ?string
    {
        return $this->locationId ? 'Редактировать локацию' : 'Создать локацию';
    }

    public function description(): ?string
    {
        return $this->locationId ? 'Изменение параметров локации' : 'Добавление новой локации';
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
                ->canSee($this->locationId !== null)
                ->confirm(__('Вы уверены, что хотите удалить эту локацию?')),
        ];
    }

    public function layout(): array
    {
        return [
            LocationEditLayout::class,
        ];
    }

    public function permission(): array
    {
        return [$this->locationId ? 'platform.locations.edit' : 'platform.locations.create'];
    }

    public function save(Request $request, Location $location): RedirectResponse
    {
        $request->validate([
            'location.name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('locations', 'name')->ignore($this->locationId),
            ],
            'location.is_active' => 'boolean',
        ]);

        $location->fill($request->input('location'))
            ->save();

        Alert::success('Локация успешно сохранена!');
        return redirect()->route('platform.locations.list');
    }

    public function remove(Location $location): RedirectResponse
    {
        $location->delete();

        Alert::success('Локация успешно удалена!');
        return redirect()->route('platform.locations.list');
    }
}
