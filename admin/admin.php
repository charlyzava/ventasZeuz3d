<?php
session_start();
// Si no hay sesión o el rol no es el correcto, expulsar
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: /index.php");
    exit();
}
// ... aquí va el contenido exclusivo del admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio de Admin</title>
</head>
<body>
    <h1>Sitio de Admin</h1>
</body>
</html>