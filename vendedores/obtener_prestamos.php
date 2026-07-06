<?php 
require '../conexion.php';
$idVendedor = $_GET['idVendedor'];

$sql = "SELECT p.*, prod.foto, prod.Nombre 
        FROM Prestamos p 
        JOIN Productos prod ON p.idProducto = prod.id 
        WHERE p.idVendedor = ? ORDER BY p.fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idVendedor]);
$prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$agrupados = [];
foreach ($prestamos as $item) {
    $fecha = $item['Fecha'];
    if (!isset($agrupados[$fecha])) {
        $agrupados[$fecha] = ['total' => 0, 'cantidad' => 0, 'items' => []];
    }
    $agrupados[$fecha]['total'] += $item['PrecioTotal'];
    $agrupados[$fecha]['cantidad'] += $item['Cantidad'];
    $agrupados[$fecha]['items'][] = $item;
}

if (count($agrupados) > 0) {
    foreach ($agrupados as $fecha => $datos) {
        $idCheckbox = "chk_" . str_replace('-', '_', $fecha);
        echo "<div class='grupo-fecha' style='margin-bottom: 10px;'>
                <input type='checkbox' id='$idCheckbox' onchange='toggleDetalle(\"$idCheckbox\")'>
                <label><strong>Fecha: $fecha</strong> | Total: \${$datos['total']} | Cant: {$datos['cantidad']}</label>
                
                <table id='detalle_$idCheckbox' style='display:none; margin: 10px 0 20px 20px; border-collapse: collapse;'>
                    <tr style='background: #f2f2f2;'><th>Foto</th><th>Producto</th><th>Cant</th><th>Total</th><th>Acción</th></tr>";
        
        foreach ($datos['items'] as $item) {
            $src = file_exists(__DIR__ . "/../productos/uploads/" . $item['foto']) ? "/productos/uploads/" . $item['foto'] : "/vendedores/placeholder.png";
            echo "<tr>
        <td data-label='Foto'><img src='{$src}' style='width:40px; border-radius:5px;'></td>
        <td data-label='Producto'>{$item['Nombre']}</td>
        <td data-label='Cant'>{$item['Cantidad']}</td>
        <td data-label='Total'>\${$item['PrecioTotal']}</td>
        <td data-label='Acción'>
            <button onclick='eliminarRegistro({$item['id']})' style='background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;'>
                Eliminar
            </button>
        </td>
      </tr>";
        }
        echo "</table></div>";
    }
} else {
    echo "<p>No hay préstamos para este vendedor.</p>";
}
?>