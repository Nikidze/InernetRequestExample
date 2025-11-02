<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('client_phone');
            $table->text('address_full');
            $table->string('status')->default('new');
            $table->timestamp('connection_time')->nullable();
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tariff_id');
            $table->unsignedBigInteger('operator_id');
            $table->unsignedBigInteger('brigade_id')->nullable();
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
            $table->foreign('tariff_id')->references('id')->on('tariffs')->nullOnDelete();
            $table->foreign('operator_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('brigade_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
