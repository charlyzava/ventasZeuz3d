<?php
session_start();
require __DIR__ . '/../conexion.php';

$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Consultamos usando las columnas actualizadas
$stmt = $pdo->prepare("SELECT usuario, passwordHash, rol FROM Usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);
$user = $stmt->fetch();

// Verificamos el hash con el nuevo nombre de columna
if ($user && password_verify($password, $user['passwordHash'])) {
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['rol'] = $user['rol'];

    if ($user['rol'] == 'admin') {
        header("Location: /admin/admin_panel.php");
    } else {
        header("Location: /vendedores/vendedor_panel.php");
    }
} else {
    echo "Usuario o contraseña incorrectos.";
}
?>