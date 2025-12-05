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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();

            // Foreign key to users table
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            //Foreign key to services table
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');

            $table->date('fecha_reserva');
            $table->time('hora_reserva');

            // estado del turno
            $table->enum('estado', ['Pendiente', 'Confirmado', 'Cancelado'])->default('Confirmado');
            $table->text('comprobante_pago');
            $table->string('ref_celular');

            $table->timestamps();

            // evita turnos duplicados
            $table->unique(['fecha_reserva', 'hora_reserva']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
