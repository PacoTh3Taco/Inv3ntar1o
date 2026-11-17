
<?php

require_once __DIR__ . '/config/conexion.php';

$busqueda = trim($_GET['buscar'] ?? '');

$sql = 'SELECT
            id,
            nombre,
            cantidad,
            stock_minimo,
            fecharegistro
        FROM productos';

if ($busqueda !== '') {
    $sql .= ' WHERE nombre LIKE :busqueda';
}

$sql .= ' ORDER BY id ASC';

$consulta = $conexion->prepare($sql);

if ($busqueda !== '') {
    $consulta->bindValue(
        ':busqueda',
        '%' . $busqueda . '%'
    );
}

$consulta->execute();
$productos = $consulta->fetchAll(PDO::FETCH_ASSOC);

$estado = $_GET['estado'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>INVENTARIO</title>

    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
</head>

<body>
    <header class="encabezado">
        <div class="contenido-encabezado">
            <p class="etiqueta">GPDS</p>

            <h1>INVENTARIO</h1>

            <p>
                Registro y consulta de productos
            </p>
        </div>
    </header>

    <main class="contenedor">
        <section class="tarjeta formulario">
            <h2>Registrar producto</h2>

            <p class="descripcion">
                Escriba el nombre, la cantidad y el stock mínimo del producto.
            </p>

            <?php if ($estado === 'guardado'): ?>
                <div class="mensaje correcto">
                    Producto registrado correctamente.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'incompleto'): ?>
                <div class="mensaje error">
                    Debe completar todos los campos.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'cantidad_invalida'): ?>
                <div class="mensaje error">
                    La cantidad debe ser un número entero mayor o igual a 0.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'stock_invalido'): ?>
                <div class="mensaje error">
                    El stock mínimo debe ser un número entero entre 0 y 50.
                </div>
            <?php endif; ?>

            <?php if ($estado === 'nombre_invalido'): ?>
                <div class="mensaje error">
                    El nombre debe tener entre 3 y 50 caracteres.
                </div>
            <?php endif; ?>

            <form action="guardar.php" method="POST">

                <div class="campo">
                    <label for="nombre">
                        Nombre del producto
                    </label>

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        maxlength="50"
                        minlength="3"
                        placeholder="Ejemplo: Café"
                        required>
                </div>

                <div class="campo">
                    <label for="cantidad">
                        Cantidad
                    </label>

                    <input
                        type="number"
                        id="cantidad"
                        name="cantidad"
                        placeholder="Ejemplo: 10"
                        min="0"
                        step="1"
                        required>
                </div>

                <div class="campo">
                    <label for="stock_minimo">
                        Stock mínimo
                    </label>

                    <input
                        type="number"
                        id="stock_minimo"
                        name="stock_minimo"
                        placeholder="Ejemplo: 5"
                        min="0"
                        max="50"
                        step="1"
                        required>
                </div>

                <button type="submit">
                    Registrar producto
                </button>
            </form>
        </section>

        <section class="tarjeta listado">
            <div class="titulo-listado">
                <div>
                    <h2>Productos registrados</h2>

                    <p class="descripcion">
                        <?php if ($busqueda !== ''): ?>
                            Resultados: <?php echo count($productos); ?>
                        <?php else: ?>
                            Total: <?php echo count($productos); ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <form method="GET" action="index.php" class="buscador">
                <label for="buscar">Buscar producto</label>

                <div class="buscador-controles">
                    <input
                        type="search"
                        id="buscar"
                        name="buscar"
                        placeholder="Escribe el nombre del producto"
                        value="<?php echo htmlspecialchars(
                            $busqueda,
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>">

                    <button type="submit">Buscar</button>

                    <a href="index.php" class="buscador-limpiar">
                        Mostrar todos
                    </a>
                </div>
            </form>

            <div class="tabla-contenedor">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Stock mínimo</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($productos) === 0): ?>
                            <tr>
                                <td colspan="6" class="sin-registros">
                                    <?php if ($busqueda !== ''): ?>
                                        No se encontraron productos con ese nombre.
                                    <?php else: ?>
                                        No hay productos registrados.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td>
                                    <?php echo (int) $producto['id']; ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $producto['nombre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php echo (int) $producto['cantidad']; ?>
                                </td>

                                <td>
                                    <?php echo (int) $producto['stock_minimo']; ?>
                                </td>

                                <td>
                                    <?php if ($producto['cantidad'] > 0): ?>
                                        <span class="estado disponible">
                                            Disponible
                                        </span>
                                    <?php else: ?>
                                        <span class="estado agotado">
                                            Sin existencia
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars(
                                        $producto['fecharegistro'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        U1. Planeación del proceso de desarrollo de software
    </footer>

    <script src="js/sweetalert2.all.min.js"></script>

    <?php if ($estado === 'guardado'): ?>
        <script>
            Swal.fire({
                title: "¡Registrado con éxito!",
                text: "¡Producto agregado correctamente!",
                icon: "success",
                timer: 3000,
                draggable: true
            });

            if (window.history.replaceState) {
                window.history.replaceState(
                    null,
                    null,
                    window.location.pathname
                );
            }
        </script>
    <?php endif; ?>
</body>

</html>
