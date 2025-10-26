<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
            $table->string('title')->nullable(); // título opcional
            $table->dateTime('start'); // início do agendamento
            $table->dateTime('end');   // término
            $table->integer('price_cents')->nullable(); // valor do atendimento
            $table->enum('payment_status', ['pending','paid'])->default('pending');
            $table->enum('status', ['scheduled','confirmed','done','canceled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
