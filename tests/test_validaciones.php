<?php
require_once __DIR__ . '/../src/validaciones.php';

$casosNombre = [
    ["Nombre valido: Teclado", "Teclado", true],
    ["Nombre vacio: Teclado", "", false],
    ["Nombre con espacios", "   ", false],
    ["Nombre valido: Mouse", "Mouse", true]
];

$casosCantidad = [
    ["Cantidad valida: Entero positivo (10)", 10, true],
    ["Cantidad valida: Cero en texto ('0')", "0", true],
    ["Cantidad invalida: Negativo (-5)", -5, false],
    ["Cantidad invalida: Texto no numerico ('abc')", "abc", false]
];


$casosStockMinimo = [
    ["Límite inferior válido (0)", 0, true],
    ["Valor intermedio válido (25)", 25, true],
    ["Límite superior válido (50)", 50, true],
    ["Valor negativo inválido (-1)", -1, false],
    ["Valor superior inválido (51)", 51, false]
];


$correctas = 0;
$fallidas = 0;

echo "=== PRUEBAS DE NOMBRE ===\n";
foreach ($casosNombre as [$descripcion, $entrada, $esperado]) {
    $obtenido = validarNombreProducto($entrada);

    if ($obtenido === $esperado) {
        echo "OK - $descripcion\n";
        $correctas++;
    } else {
        echo "FAIL - $descripcion\n";
        echo " Esperado: " . var_export($esperado, true) . "\n";
        echo " Obtenido: " . var_export($obtenido, true) . "\n";
        $fallidas++;
    }
}

echo "\n=== PRUEBAS DE CANTIDAD ===\n";
foreach ($casosCantidad as [$descripcion, $entrada, $esperado]) {
    $obtenido = validarCantidadProducto($entrada);

    if ($obtenido === $esperado) {
        echo "OK - $descripcion\n";
        $correctas++;
    } else {
        echo "FAIL - $descripcion\n";
        echo " Esperado: " . var_export($esperado, true) . "\n";
        echo " Obtenido: " . var_export($obtenido, true) . "\n";
        $fallidas++;
    }
}

echo "\n=== PRUEBAS DE STOCK MÍNIMO ===\n";

foreach ($casosStockMinimo as [$descripcion, $entrada, $esperado]) {
    $obtenido = validarStockMinimoProducto($entrada);

    if ($obtenido === $esperado) {
        echo "OK - $descripcion\n";
        $correctas++;
    } else {
        echo "FAIL - $descripcion\n";
        echo " Esperado: " . var_export($esperado, true) . "\n";
        echo " Obtenido: " . var_export($obtenido, true) . "\n";
        $fallidas++;
    }
}


$total = count($casosNombre) + count($casosCantidad) + count($casosStockMinimo);

echo "\n--RESUMEN--\n";
echo "Total: $total\n";
echo "Correctas: $correctas\n";
echo "Fallidas: $fallidas\n";

exit($fallidas > 0 ? 1 : 0);