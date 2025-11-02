<?php

test('система провайдера загружается корректно', function () {
    $response = $this->get('/admin');

    $response->assertStatus(302);
    $response->assertRedirect('/admin/login');
});

test('тестовые пользователи создаются с правильными ролями', function () {
    $director = \App\Models\User::where('email', 'director@example.com')->first();
    $operator = \App\Models\User::where('email', 'operator@example.com')->first();
    $brigade = \App\Models\User::where('email', 'brigade@example.com')->first();

    expect($director)->not->toBeNull();
    expect($operator)->not->toBeNull();
    expect($brigade)->not->toBeNull();

    expect($director->roles->first()->slug)->toBe('director');
    expect($operator->roles->first()->slug)->toBe('operator');
    expect($brigade->roles->first()->slug)->toBe('brigade');
});

test('тарифы создаются с правильными параметрами', function () {
    $tariffs = \App\Models\Tariff::all();

    expect($tariffs)->not->toBeEmpty();
    expect($tariffs->count())->toBeGreaterThanOrEqual(5);

    $basicTariff = $tariffs->where('name', 'Базовый')->first();
    expect($basicTariff)->not->toBeNull();
    expect($basicTariff->speed)->toBe(10);
    expect($basicTariff->price)->toBe(500);
});

test('локации создаются правильно', function () {
    $locations = \App\Models\Location::all();

    expect($locations)->not->toBeEmpty();

    $expectedLocations = ['Мирный', 'Трудовой', 'Майский', 'Пролетарский', 'Комсомольский'];
    foreach ($expectedLocations as $locationName) {
        $location = $locations->where('name', $locationName)->first();
        expect($location)->not->toBeNull();
    }
});

test('заявки создаются с правильными статусами', function () {
    $orders = \App\Models\Order::all();

    expect($orders)->not->toBeEmpty();

    $statuses = $orders->pluck('status')->unique();
    expect($statuses)->toContain('new');
    expect($statuses)->toContain('in_progress');
    expect($statuses)->toContain('completed');
    expect($statuses)->toContain('canceled');
});

test('методы проверки ролей работают корректно', function () {
    $director = \App\Models\User::where('email', 'director@example.com')->first();
    $operator = \App\Models\User::where('email', 'operator@example.com')->first();
    $brigade = \App\Models\User::where('email', 'brigade@example.com')->first();

    expect($director->roles->where('slug', 'director')->isNotEmpty())->toBeTrue();
    expect($operator->roles->where('slug', 'operator')->isNotEmpty())->toBeTrue();
    expect($brigade->roles->where('slug', 'brigade')->isNotEmpty())->toBeTrue();
});
