<?php
require __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $nuevaPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Actualizamos usando la columna passwordHash y limpiamos el tokenRecuperacion
    $stmt = $pdo->prepare("UPDATE Usuarios 
                           SET passwordHash = ?, tokenRecuperacion = NULL 
                           WHERE tokenRecuperacion = ?");
    $stmt->execute([$nuevaPassword, $token]);

    echo "Contraseña actualizada correctamente.";
}
?>