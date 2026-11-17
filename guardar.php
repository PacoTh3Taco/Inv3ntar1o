<?php

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/src/productos.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nombre = $_POST['nombre'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$stockMinimo = $_POST['stock_minimo'] ?? '';

try {
    guardarProducto($conexion, $nombre, $cantidad, $stockMinimo);
    header('Location: index.php?estado=guardado');
} catch (InvalidArgumentException $error) {
    $estado = $error->getMessage();
    header('Location: index.php?estado=' . urlencode($estado));
}

exit;
