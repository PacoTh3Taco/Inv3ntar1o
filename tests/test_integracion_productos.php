<?php

require_once __DIR__ . '/conexion_pruebas.php';
require_once __DIR__ . '/../src/productos.php';

$correctas = 0;
$fallidas = 0;

try {
    $conexion->beginTransaction();

    echo "=== PRUEBAS DE INTEGRACIÓN ===\n";

    // Prueba 1: guardar y recuperar un producto válido
    $id = guardarProducto($conexion, 'Producto Prueba', '10', '5');

    $consulta = $conexion->prepare(
        'SELECT nombre, cantidad, stock_minimo
         FROM productos WHERE id = :id'
    );
    $consulta->execute(['id' => $id]);
    $producto = $consulta->fetch(PDO::FETCH_ASSOC);

    if (
        $producto !== false &&
        $producto['nombre'] === 'Producto Prueba' &&
        (int) $producto['cantidad'] === 10 &&
        (int) $producto['stock_minimo'] === 5
    ) {
        echo "OK - Producto válido guardado y recuperado correctamente\n";
        $correctas++;
    } else {
        echo "FAIL - Los datos guardados no coinciden\n";
        $fallidas++;
    }

    // Prueba 2: rechazar un stock mínimo inválido
    $consulta = $conexion->query(
        'SELECT COUNT(*) FROM productos'
    );
    $registrosAntes = (int) $consulta->fetchColumn();

    $rechazado = false;

    try {
        guardarProducto($conexion, 'Producto Inválido', '10', '51');
    } catch (InvalidArgumentException $error) {
        $rechazado = $error->getMessage() === 'stock_invalido';
    }

    $consulta = $conexion->query(
        'SELECT COUNT(*) FROM productos'
    );
    $registrosDespues = (int) $consulta->fetchColumn();

    if ($rechazado && $registrosAntes === $registrosDespues) {
        echo "OK - Stock inválido rechazado sin crear registros\n";
        $correctas++;
    } else {
        echo "FAIL - No se rechazó correctamente el producto inválido\n";
        $fallidas++;
    }

} catch (Throwable $error) {
    echo "ERROR - " . $error->getMessage() . "\n";
    $fallidas++;
} finally {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
        echo "Limpieza: transacción revertida\n";
    }
}

echo "\n=== RESUMEN ===\n";
echo "Correctas: $correctas\n";
echo "Fallidas: $fallidas\n";

exit($fallidas > 0 || $correctas !== 2 ? 1 : 0);
