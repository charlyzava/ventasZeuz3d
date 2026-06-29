<?php
require 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 1. Primero buscamos el nombre de la foto para poder borrarla del servidor
        $stmt = $pdo->prepare("SELECT foto FROM Productos WHERE id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            // Si tiene foto asignada y el archivo existe en la carpeta, lo borramos
            if (!empty($producto['foto']) && file_exists('uploads/' . $producto['foto'])) {
                unlink('uploads/' . $producto['foto']);
            }

            // 2. Eliminamos el registro de la base de datos
            $deleteStmt = $pdo->prepare("DELETE FROM Productos WHERE id = ?");
            $deleteStmt->execute([$id]);
        }
    } catch (Exception $e) {
        // Si hay un error, puedes guardarlo en un log o mostrarlo (opcional)
        // Usamos un JavaScript rápido para avisar si algo falló antes de volver
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "');</script>";
    }
}

// Redireccionar de vuelta a la página principal (cambia 'index.php' si tu archivo se llama distinto)
header("Location: productos.php");
exit;
?>