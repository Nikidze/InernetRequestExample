<?php

namespace App\Orchid\Screens\Order;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Orchid\Layouts\Order\OrderEditLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class OrderEditScreen extends Screen
{
    public ?int $orderId = null;

    public function query(Order $order): array
    {
        $user = auth()->user();

        $this->orderId = $order->exists ? $order->id : null;

        return [
            "order" => $order->load([
                "tariff",
                "location",
                "operator",
                "brigade",
            ]),
        ];
    }

    public function name(): ?string
    {
        return $this->orderId ? "Редактировать заявку" : "Создать заявку";
    }

    public function description(): ?string
    {
        return $this->orderId
            ? "Изменение заявки на подключение"
            : "Создание новой заявки на подключение";
    }

    public function commandBar(): array
    {
        $user = auth()->user();
        $commandBar = [];

        if ($user->hasRole("director") || $user->hasRole("operator")) {
            $commandBar[] = Button::make(__("Сохранить"))
                ->icon("bs.check-circle")
                ->method("save");
        } elseif ($user->hasRole("brigade")) {
            $commandBar[] = Button::make(__("Обновить статус"))
                ->icon("bs.check-circle")
                ->method("updateStatus");
        }

        if ($user->hasRole("director") || $user->hasRole("operator")) {
            $commandBar[] = Button::make(__("Удалить"))
                ->icon("bs.trash3")
                ->method("remove")
                ->canSee($this->orderId !== null)
                ->confirm(__("Вы уверены, что хотите удалить эту заявку?"));
        }

        return $commandBar;
    }

    public function layout(): array
    {
        return [OrderEditLayout::class];
    }

    public function permission(): array
    {
        $user = auth()->user();

        if ($user->hasRole("brigade")) {
            return ["platform.orders.complete"];
        }

        return [
            $this->orderId ? "platform.orders.edit" : "platform.orders.create",
        ];
    }

    public function save(Request $request, Order $order): RedirectResponse
    {
        $user = auth()->user();

        $orderData = $request->input("order");

        if (!isset($orderData["operator_id"])) {
            if (!$user->hasRole("operator")) {
                Alert::error(
                    "Вы не являетесь оператором и не можете создать заявку без указания оператора.",
                );
                return redirect()->back();
            }
            $orderData["operator_id"] = $user->id;
        }

        $request->merge(["order" => $orderData]);

        $request->validate([
            "order.client_name" => "required|string|max:255",
            "order.client_phone" => "required|string|max:20",
            "order.address_full" => "required|string|max:1000",
            "order.location_id" => "required|exists:locations,id",
            "order.tariff_id" => "required|exists:tariffs,id",
            "order.operator_id" => "required|exists:users,id",
            "order.connection_time" => "required|date|after:now",
            "order.status" =>
                "required|in:" .
                implode(",", array_column(OrderStatus::cases(), "value")),
            "order.brigade_id" => "nullable|exists:users,id",
        ]);

        $order->fill($request->input("order"))->save();

        Alert::success("Заявка успешно сохранена!");

        return redirect()->route("platform.orders.list");
    }

    public function updateStatus(
        Request $request,
        Order $order,
    ): RedirectResponse|null {
        $user = auth()->user();

        if (
            !$user->hasRole("brigade") ||
            $user->id !== $order->brigade_id ||
            $order->status !== OrderStatus::IN_PROGRESS->value
        ) {
            Alert::error("У вас нет прав для изменения этой заявки");

            return null;
        }

        $request->validate([
            "order.status" =>
                "required|in:" .
                OrderStatus::IN_PROGRESS->value .
                "," .
                OrderStatus::COMPLETED->value .
                "," .
                OrderStatus::CANCELED->value,
        ]);

        $status = $request->input("order.status");

        // Бригада может только завершить или отменить заявку
        if (
            !in_array($status, [
                OrderStatus::COMPLETED->value,
                OrderStatus::CANCELED->value,
            ])
        ) {
            Alert::error("Вы можете только завершить или отменить заявку");

            return null;
        }

        $order->status = $status;
        $order->save();

        $message =
            $status === OrderStatus::COMPLETED->value
                ? "Заявка успешно выполнена!"
                : "Заявка успешно отменена!";
        Alert::success($message);

        return redirect()->route("platform.orders.list");
    }

    public function remove(Order $order): RedirectResponse
    {
        $order->delete();

        Alert::success("Заявка успешно удалена!");
        return redirect()->route("platform.orders.list");
    }
}
