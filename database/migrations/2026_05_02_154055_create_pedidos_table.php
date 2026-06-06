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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('data_pedido')->useCurrent();
            $table->decimal('total', 10, 2);
            $table->string('metodo_pagamento')->nullable();
            $table->string('status')->default('pendente');
            $table->string('codigo_rastreio')->nullable();
            $table->timestamp('data_entrega_prevista')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
