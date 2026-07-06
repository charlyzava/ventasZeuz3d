<?php
require '../conexion.php';

if (isset($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM Prestamos WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    echo "success";
}
?>