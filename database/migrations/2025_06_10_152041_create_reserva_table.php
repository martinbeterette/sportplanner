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
        Schema::create('reserva', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->date('fecha_reserva');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->foreignId('rela_persona')->constrained('persona')->onDelete('restrict');
            $table->foreignId('rela_zona')->constrained('zona')->onDelete('restrict');
            $table->foreignId('rela_horario')->constrained('horario')->onDelete('restrict');
            $table->foreignId('rela_estado_reserva')->constrained('estado_reserva')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserva');
    }
};
