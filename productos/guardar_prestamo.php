<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Iniciamos la transacción
        $pdo->beginTransaction();

        // 1. Insertar el préstamo
        $sql = "INSERT INTO Prestamos (Fecha, idVendedor, idProducto, Cantidad, PrecioTotal) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['fechaPrestamo'],
            $_POST['idVendedor'],
            $_POST['idProducto'],
            $_POST['cantidad'],
            $_POST['precioTotal']
        ]);

        // 2. Descontar el Stock en la tabla Productos
        // Usamos una sentencia preparada para evitar inyecciones SQL
        $sqlStock = "UPDATE Productos SET stock = stock - ? WHERE id = ?";
        $stmtStock = $pdo->prepare($sqlStock);
        $stmtStock->execute([
            $_POST['cantidad'], 
            $_POST['idProducto']
        ]);

        // Confirmamos ambos cambios
        $pdo->commit();

        header("Location: productos.php?status=prestamo_ok");
        exit;

    } catch (Exception $e) {
        // Si algo falla, revertimos todos los cambios
        $pdo->rollBack();
        echo "Error al procesar el préstamo: " . $e->getMessage();
    }
}
?>