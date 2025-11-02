@php
    $order = $order ?? null;
@endphp

@if($order)
    <div class="client-cell">
        <div class="fw-bold text-primary">
            <a href="{{ route('platform.orders.edit', $order) }}" class="text-decoration-none">
                {{ $order->client_name }}
            </a>
        </div>
        <div class="text-muted small">
            <a href="tel:{{ $order->client_phone }}" class="text-decoration-none">
                {{ $order->client_phone }}
            </a>
        </div>
    </div>
@endif