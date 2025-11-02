<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Fields\Input;

class ClientFilter extends Filter
{
    public function run(Builder $builder): Builder
    {
        $value = $this->request->get('filter');
        $value = array_key_exists('client', $value ?? []) ? $value['client'] : null;

        if (! $value) {
            return $builder;
        }

        return $builder->where(function ($query) use ($value) {
            $query->where('client_name', 'LIKE', "%{$value}%")
                ->orWhere('client_phone', 'LIKE', "%{$value}%");
        });
    }

    public function display(): array
    {
        return [
            Input::make('client')
                ->placeholder('Поиск по имени или телефону')
                ->value($this->request->get('client'))
                ->title('Клиент'),
        ];
    }
}
