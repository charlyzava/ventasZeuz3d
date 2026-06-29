<?php
// Tu código de conexión a la base de datos iría aquí
include 'conexion.php'; 
// 3. Consultar datos para mostrar
$stmt = $pdo->query("SELECT * FROM vendedores");
$registros = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zeuz 3d - Portal Ventas</title>
</head>
<body>
    <h1>Zeuz 3d - Portal de Ventas</h1>

    <h3>Bienvenido!</h3>

    <h2>Registros guardados:</h2>
    <ul>
        <?php foreach ($registros as $fila): ?>
            <li><?php echo htmlspecialchars($fila['Nombre']); ?></li>
        <?php endforeach; ?>
    </ul>
        <a href="./productos/productos.php">Productos</a>
</body>
</html>