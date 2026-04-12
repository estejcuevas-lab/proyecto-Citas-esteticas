<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Arquitectura y Diseno de Software
 */

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ======================================================================
        // GUIA 6 - ACTIVIDAD 1: DISENO DE BASE DE DATOS
        // La carga semilla permite poblar el modelo relacional con datos iniciales para pruebas funcionales.
        // ======================================================================
        $this->call([
            UserSeeder::class,
            BusinessSeeder::class,
            BusinessHourSeeder::class,
            ServiceSeeder::class,
            AppointmentSeeder::class,
        ]);
    }
}
