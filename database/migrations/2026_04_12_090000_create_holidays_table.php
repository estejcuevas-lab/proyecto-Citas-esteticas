<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 1: DISENO DE BASE DE DATOS
        // Esta migracion define la estructura SQL para persistir festivos sincronizados desde servicios externos.
        // ======================================================================
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('holiday_date');
            $table->string('name');
            $table->string('country_code', 2)->default('CO');
            $table->string('source')->default('nager_date');
            $table->timestamps();

            $table->unique(['holiday_date', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
