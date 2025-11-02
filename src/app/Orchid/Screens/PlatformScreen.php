<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Информационная система интернет-провайдера';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Система управления заявками на подключение к интернету. Включает роли: Директор (управление тарифами и населенными пунктами), Оператор (создание, просмотр и подтверждение заявок), Бригада (выполнение и завершение заявок). Заявки проходят статусы: Новая -> В работе -> Завершена/Отменена.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [];
    }
}
