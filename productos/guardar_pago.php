<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "INSERT INTO Pagos (idVendedor, idProducto, Cantidad, Monto, TipoPago, Fecha) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['idVendedor'],
        $_POST['idProducto'],
        $_POST['cantidad'],
        $_POST['monto'],
        $_POST['tipoPago'],
        $_POST['fecha']
    ]);
    header("Location: productos.php?status=pago_ok");
}
?>