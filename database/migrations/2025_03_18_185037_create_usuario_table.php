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
        Schema::create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->text('password');
            $table->boolean('verificacion')->default(false);
            $table->foreignId('rela_contacto')->constrained('contacto')->onDelete('restrict');
            $table->foreignId('rela_rol')->constrained('rol')->onDelete('restrict');
            $table->boolean('activo')->default(true);
            $table->text('token')->nullable();
            $table->timestamp('token_expira_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
