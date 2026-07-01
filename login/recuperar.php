<?php
require __DIR__ . '/../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(32)); // Genera un token seguro
    
    // Guardamos el token en la base de datos
    $stmt = $pdo->prepare("UPDATE Usuarios SET tokenRecuperacion = ? WHERE email = ?");
    $stmt->execute([$token, $email]);
    
    // Aquí iría el código con PHPMailer para enviar el link al correo
    // Ejemplo: link = "tusitio.com/reset.php?token=" . $token
    echo "Se ha enviado un enlace de recuperación a tu correo.";
}
?>