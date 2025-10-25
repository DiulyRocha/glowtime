<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// database/migrations/2025_01_01_000000_create_appointments_table.php
return new class extends Migration {
    public function up(): void {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('professional_id')->constrained()->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->integer('price_cents'); // valor do atendimento
            $table->enum('payment_status', ['pending','paid'])->default('pending');
            $table->enum('status', ['scheduled','confirmed','done','canceled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->string('title'); // nome ou tipo do serviço
        $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
        $table->foreignId('professional_id')->constrained('professionals')->onDelete('cascade');
        $table->dateTime('start'); // início do agendamento
        $table->dateTime('end');   // término
        $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending');
        $table->integer('valor')->nullable();
        $table->timestamps();
    });
}
