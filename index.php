<?php
/** @var PDO $pdo */
require 'conexion.php';

try {
    // Consulta para obtener los productos
    $stmt = $pdo->query("SELECT * FROM Productos ORDER BY id DESC LIMIT 15");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $productos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda ZEUZ 3D</title>
    <link rel="stylesheet" href="estilos-publicos.css">
</head>
<body>

    <nav>
        <div class="logo">ZEUZ 3D</div>
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Productos</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>

    <section class="slider">
        <!-- Aquí podrías poner tu slider más adelante -->
        <h2>Bienvenidos a ZEUZ 3D</h2>
    </section>

    <main class="galeria">
        <?php if (count($productos) > 0): ?>
            <?php foreach ($productos as $prod): ?>
                <div class="card">
                    <?php 
                    // Ruta directa usando la estructura que mencionaste
                    $imgRuta = 'productos/uploads/' . htmlspecialchars($prod['Foto']);
                    echo $imgRuta; 
                    ?>
                    <img src="<?php echo $imgRuta; ?>" alt="<?php echo htmlspecialchars($prod['Nombre']); ?>" onclick="abrirModal(this.src)">
                    <h3><?php echo htmlspecialchars($prod['Nombre']); ?></h3>
                    <p class="precio">$<?php echo htmlspecialchars($prod['PrecioFinal']); ?></p>
                    <div class="botones">
                        <button class="btn-comprar">Comprar</button>
                        <button class="btn-ver">Ver más</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles por el momento.</p>
        <?php endif; ?>
    </main>

    <!-- Ventana Modal -->
    <div id="miModal" class="modal" onclick="cerrarModal()">
        <span class="cerrar">&times;</span>
        <img class="modal-contenido" id="imgAmpliada">
    </div>

    <script>
        function abrirModal(src) {
            document.getElementById("imgAmpliada").src = src;
            document.getElementById("miModal").style.display = "flex";
        }
        function cerrarModal() {
            document.getElementById("miModal").style.display = "none";
        }
    </script>
</body>
</html>