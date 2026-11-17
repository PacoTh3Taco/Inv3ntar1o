
<?php

require_once __DIR__ . '/validaciones.php';

function guardarProducto(PDO $conexion, $nombre, $cantidad, $stockMinimo): int
{
    if (!is_string($nombre) || !is_string($cantidad) || !is_string($stockMinimo)) {
        throw new InvalidArgumentException('incompleto');
    }

    $nombre = trim($nombre);
    $cantidad = trim($cantidad);
    $stockMinimo = trim($stockMinimo);

    if ($nombre === '' || $cantidad === '' || $stockMinimo === '') {
        throw new InvalidArgumentException('incompleto');
    }

    if (!validarNombreProducto($nombre)) {
        throw new InvalidArgumentException('nombre_invalido');
    }

    if (!validarCantidadProducto($cantidad)) {
        throw new InvalidArgumentException('cantidad_invalida');
    }

    if (!validarStockMinimoProducto($stockMinimo)) {
        throw new InvalidArgumentException('stock_invalido');
    }

    $sentencia = $conexion->prepare(
        'INSERT INTO productos (nombre, cantidad, stock_minimo)
         VALUES (:nombre, :cantidad, :stock_minimo)'
    );

    $sentencia->execute([
        'nombre' => $nombre,
        'cantidad' => (int) $cantidad,
        'stock_minimo' => (int) $stockMinimo
    ]);

    return (int) $conexion->lastInsertId();
}
