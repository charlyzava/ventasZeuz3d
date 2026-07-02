<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "UPDATE Productos SET 
            nombre = ?, 
            preciofinal = ?, 
            preciovendedor = ?, 
            preciomayorista = ?, 
            costo = ?, 
            stock = ?, 
            tiempoimpresion = ?, 
            tiempopostprocesado = ? 
            WHERE id = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['nombre'],
        str_replace(',', '.', $_POST['preciofinal']),
        str_replace(',', '.', $_POST['preciovendedor']),
        str_replace(',', '.', $_POST['preciomayorista']),
        str_replace(',', '.', $_POST['costo']),
        (int)$_POST['stock'],
        $_POST['tiempoimpresion'],
        $_POST['tiempopostprocesado'],
        $_POST['id']
    ]);

    header("Location: productos.php?status=updated");
    exit;
}
?>