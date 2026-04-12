<?php

/**
 * AUTORES: Erick Cuevas- Camilo Ramirez
 * MATERIA: Cliente-Servidor
 */

$config = require __DIR__.'/bootstrap.php';

use Distributed\Registry\RegistryServer;

// ======================================================================
// GUIA 6 - ACTIVIDAD 1: MODULO REGISTRY
// Este proceso centraliza el directorio de servicios remotos disponibles.
// ======================================================================
$registry = new RegistryServer(
    $config['registry']['host'],
    $config['registry']['port']
);

$registry->start();
