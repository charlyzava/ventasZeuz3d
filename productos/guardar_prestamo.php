<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Definimos la consulta esperando 5 valores
    $sql = "INSERT INTO Prestamos (Fecha, idVendedor, idProducto, Cantidad, PrecioTotal) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    // Enviamos los 5 valores en el mismo orden que en la consulta SQL
    $stmt->execute([
        $_POST['fechaPrestamo'], // 1. La fecha que viene del input
        $_POST['idVendedor'],    // 2. El vendedor
        $_POST['idProducto'],    // 3. El producto
        $_POST['cantidad'],      // 4. La cantidad
        $_POST['precioTotal']    // 5. El total
    ]);
    
    header("Location: productos.php?status=prestamo_ok");
}
?>