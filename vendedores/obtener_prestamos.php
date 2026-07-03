<?php
require '../conexion.php';

$idVendedor = $_GET['idVendedor'];

// Buscamos préstamos y unimos con productos para obtener la imagen
$sql = "SELECT p.*, prod.foto 
        FROM Prestamos p 
        JOIN Productos prod ON p.idProducto = prod.id 
        WHERE p.idVendedor = ? ORDER BY p.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idVendedor]);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($prestamos) > 0) {
    echo "<table>
            <tr><th>Imagen</th><th>ID</th><th>Fecha</th><th>ID Prod</th><th>Cant</th><th>Total</th></tr>";
    foreach ($prestamos as $item) {
    // 1. Ruta para verificar en el servidor (usando dirname para asegurar la posición)
    $ruta_servidor = __DIR__ . "/../productos/uploads/" . $item['foto'];
    
    // 2. Ruta para el navegador (URL absoluta desde la raíz del dominio)
    $src_web = "/productos/uploads/" . $item['foto'];
    
    // 3. Ruta para el placeholder (asegúrate de que sea accesible desde cualquier lugar)
    $placeholder = "/vendedores/placeholder.png";
    
    // Validamos y asignamos
    $src = file_exists($ruta_servidor) ? $src_web : $placeholder;
    
    echo "<tr>
            <td><img src='{$src}' style='width:50px;'></td>
            <td>{$item['id']}</td>
            <td>{$item['Fecha']}</td>
            <td>{$item['idProducto']}</td>
            <td>{$item['Cantidad']}</td>
            <td>\${$item['PrecioTotal']}</td>
          </tr>";
}
    echo "</table>";
} else {
    echo "<p>No hay préstamos para este vendedor.</p>";
}
?>