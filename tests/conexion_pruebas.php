<?php

$servidor = getenv('DB_HOST') ?: 'localhost';
$baseDatos = getenv('DB_NAME') ?: 'invent_test';
$usuario = getenv('DB_USER') ?: 'root';


if (
    !str_ends_with($baseDatos, '_test') ||
    $baseDatos === 'inventario'
) {
    throw new RuntimeException(
        'Las pruebas solo pueden utilizar una base de datos terminada en _test.'
    );
}


$contrasena = getenv('DB_PASSWORD');

if ($contrasena === false) {
    $contrasena = '';
}

$conexion = new PDO(
    "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
    $usuario,
    $contrasena,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
);