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

<button onclick="document.getElementById('modalLogin').style.display='flex'" 
        style="position: absolute; top: 20px; right: 20px;">Acceso</button>

<div id="modalLogin" class="modal-foto" style="display:none; justify-content:center; align-items:center;">
    <form action="./login/login.php" method="POST" style="background:white; padding:20px; border-radius:8px;">
        <h3>Iniciar Sesión</h3>
        <input type="text" name="usuario" placeholder="Usuario" required><br>
        <input type="password" name="password" placeholder="Contraseña" required><br>
        <button type="submit">Ingresar</button>
        <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
    </form>
</div>
    <h1>Zeuz 3d - Portal de Ventas</h1>

    <h3>Bienvenido!</h3>

    <h2>Registros guardados:</h2>
    <ul>
        <?php foreach ($registros as $fila): ?>
            <li><?php echo htmlspecialchars($fila['Nombre']); ?></li>
        <?php endforeach; ?>
    </ul>
        <a href="./productos/productos.php">Productos</a>
        <a href="./vendedores/vendedores.php">Vendedores</a>
</body>
</html>