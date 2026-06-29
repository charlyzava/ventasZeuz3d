<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // 0. Verificar si el nombre ya existe
        $checkSql = "SELECT COUNT(*) FROM Productos WHERE nombre = ?";
        $stmtCheck = $pdo->prepare($checkSql);
        $stmtCheck->execute([$_POST['nombre']]);
        
        if ($stmtCheck->fetchColumn() > 0) {
            // Si el conteo es mayor a 0, el producto ya existe
            echo "Error: Ya existe un producto con el nombre '" . htmlspecialchars($_POST['nombre']) . "'.<br><br>";
            echo '<a href="javascript:history.back()" style="padding: 10px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 5px;">Volver al formulario</a>';
            exit; 
        }

        // 1. Insertar datos en la base de datos
        $sql = "INSERT INTO Productos (nombre, preciofinal, preciovendedor, preciomayorista, costo, tiempoimpresion, tiempopostprocesado) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $pdo->prepare($sql)->execute([
            $_POST['nombre'],
            str_replace(',', '.', $_POST['preciofinal']),
            str_replace(',', '.', $_POST['preciovendedor']),
            str_replace(',', '.', $_POST['preciomayorista']),
            str_replace(',', '.', $_POST['costo']),
            $_POST['tiempoimpresion'],
            $_POST['tiempostprocesado']
        ]);
        
        // 2. Obtener el ID recién creado
        $nuevoId = $pdo->lastInsertId();
        
        // 3. Procesar, redimensionar y convertir imagen a WebP
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $rutaDestino = 'uploads/' . $nuevoId . '.webp';
            
            // Detectar tipo y crear recurso original
            $info = getimagesize($_FILES['foto']['tmp_name']);
            $anchoOriginal = $info[0];
            $altoOriginal = $info[1];
            $mime = $info['mime'];

            if ($mime == 'image/jpeg') $imgOriginal = imagecreatefromjpeg($_FILES['foto']['tmp_name']);
            elseif ($mime == 'image/png') $imgOriginal = imagecreatefrompng($_FILES['foto']['tmp_name']);
            else die("Formato no soportado.");

            // --- LÓGICA DE REDIMENSIONADO PROPORCIONAL (Máx 700x700) ---
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

            // Crear lienzo nuevo con las medidas calculadas
            $imgRedimensionada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

            // Mantener transparencias si el origen era PNG
            if ($mime == 'image/png') {
                imagealphablending($imgRedimensionada, false);
                imagesavealpha($imgRedimensionada, true);
            }

            // Copiar y cambiar el tamaño de la imagen original a la nueva
            imagecopyresampled(
                $imgRedimensionada, $imgOriginal, 
                0, 0, 0, 0, 
                $nuevoAncho, $nuevoAlto, $anchoOriginal, $altoOriginal
            );

            // Convertir a WebP y guardar
            imagewebp($imgRedimensionada, $rutaDestino, 80);
            
            // Liberar memoria
            imagedestroy($imgOriginal);
            imagedestroy($imgRedimensionada);

            // 4. Actualizar la base de datos con el nombre del archivo
            $pdo->prepare("UPDATE Productos SET foto = ? WHERE id = ?")->execute([$nuevoId . '.webp', $nuevoId]);
        }

        // --- CAMBIO AQUÍ: Redirección automática al formulario con aviso de éxito ---
        // (Asegúrate de cambiar 'index.php' por el nombre real de tu archivo del formulario si se llama diferente)
        header("Location: productos.php?status=success");
        exit;

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>