<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    if (isset($_FILES['nueva_foto']) && $_FILES['nueva_foto']['error'] === UPLOAD_ERR_OK) {
        try {
            // 1. Buscar si ya existe una foto vieja asignada para borrarla físicamente
            $stmt = $pdo->prepare("SELECT foto FROM Productos WHERE id = ?");
            $stmt->execute([$id]);
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($producto && !empty($producto['foto']) && file_exists('uploads/' . $producto['foto'])) {
                unlink('uploads/' . $producto['foto']);
            }

            // 2. Procesar, redimensionar y convertir la NUEVA imagen a WebP
            $rutaDestino = 'uploads/' . $id . '.webp';
            
            $info = getimagesize($_FILES['nueva_foto']['tmp_name']);
            $anchoOriginal = $info[0];
            $altoOriginal = $info[1];
            $mime = $info['mime'];

            if ($mime == 'image/jpeg') $imgOriginal = imagecreatefromjpeg($_FILES['nueva_foto']['tmp_name']);
            elseif ($mime == 'image/png') $imgOriginal = imagecreatefrompng($_FILES['nueva_foto']['tmp_name']);
            else die("Formato no soportado.");

            // Lógica de redimensionado proporcional (Máx 700x700)
            $maxDim = 700;
            $nuevoAncho = $anchoOriginal;
            $nuevoAlto = $altoOriginal;

            if ($anchoOriginal > $maxDim || $altoOriginal > $maxDim) {
                if ($anchoOriginal > $altoOriginal) {
                    $nuevoAncho = $maxDim;
                    $nuevoAlto = round(($altoOriginal / $anchoOriginal) * $maxDim);
                } else {
                    $nuevoAlto = $maxDim;
                    $nuevoAncho = round(($anchoOriginal / $altoOriginal) * $maxDim);
                }
            }

            $imgRedimensionada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

            if ($mime == 'image/png') {
                imagealphablending($imgRedimensionada, false);
                imagesavealpha($imgRedimensionada, true);
            }

            imagecopyresampled(
                $imgRedimensionada, $imgOriginal, 
                0, 0, 0, 0, 
                $nuevoAncho, $nuevoAlto, $anchoOriginal, $altoOriginal
            );

            // Guardar en WebP
            imagewebp($imgRedimensionada, $rutaDestino, 80);
            
            imagedestroy($imgOriginal);
            imagedestroy($imgRedimensionada);

            // 3. Asegurar que la BD refleje el nombre correcto de la foto (por si antes no tenía)
            $pdo->prepare("UPDATE Productos SET foto = ? WHERE id = ?")->execute([$id . '.webp', $id]);

        } catch (Exception $e) {
            echo "<script>alert('Error al actualizar la foto: " . $e->getMessage() . "');</script>";
        }
    }
}

// Redireccionar de vuelta a la página de la tabla
header("Location: productos.php"); 
exit;
?>