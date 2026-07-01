<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO Prestamos (fecha, idVendedor, idProducto, Cantidad, PrecioTotal) VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['idVendedor'],
        $_POST['idProducto'],
        $_POST['cantidad'],
        $_POST['precioTotal']
    ]);
    header("Location: productos.php?status=prestamo_ok");
}
?>