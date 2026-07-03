<?php
/** @var PDO $pdo */
require 'conexion.php';

try {
    $stmt = $pdo->query("SELECT * FROM Productos ORDER BY id DESC LIMIT 20");
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error al cargar la tabla: " . $e->getMessage();
    $productos = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Cargar Producto</title>
    <link rel="stylesheet" href="./estilos.css">
</head>
<body>
<a href="../index.php">Home</a>
    <div class="contenedor">
        <h1>Productos</h1>
        <form action="guardar.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nombre" placeholder="Nombre" required><br>
            <input type="text" name="preciofinal" placeholder="Precio Final"><br>
            <input type="text" name="preciovendedor" placeholder="Precio Vendedor"><br>
            <input type="text" name="preciomayorista" placeholder="Precio Mayorista"><br>
            <input type="text" name="costo" placeholder="Costo"><br>
            <input type="number" name="tiempoimpresion" placeholder="Tiempo Impresión"><br>
            <input type="number" name="tiempopostprocesado" placeholder="Tiempo Post-Procesado"><br>
            <input type="number" name="stock" placeholder="Stock Inicial" required><br>
            <input type="file" name="foto" accept="image/*" required><br>
            <button type="submit">Guardar Producto</button>
        </form>
    </div>

    <div id="mensaje"></div>

    <div class="contenedor-tabla">
        <h2>Últimos 20 registros</h2>
        <div style="margin: 20px 0;">
            <input type="text" id="buscador" placeholder="Buscar por nombre..." onkeyup="filtrarTabla()" style="padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
        </div>

        <div style="margin: 20px 0; font-family: sans-serif;">
            <label style="cursor: pointer; user-select: none;">
                <input type="checkbox" id="chkMostrarDetalles" onchange="alternarColumnas()"> 
                <strong>Mostrar Costo y Tiempos de Fabricación</strong>
            </label>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Precio Final</th>
                    <th>Precio Vendedor</th>
                    <th>Precio Mayorista</th>
                    <th class="col-detalle">Costo</th> 
                    <th class="col-detalle">T. Impresión</th> 
                    <th class="col-detalle">T. Post-Procesado</th> 
                    <th>Stock</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $prod): ?>
                        <tr>
                            <td>
                                <?php if (!empty($prod['foto']) && file_exists('uploads/' . $prod['foto'])): ?>
                                    <img src="uploads/<?php echo $prod['foto']; ?>" alt="Foto" class="img-tabla" style="cursor: pointer;" onclick="abrirModal(this.src)">
                                <?php else: ?>
                                    <div style="width: 70px; height: 70px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #777;">Sin foto</div>
                                <?php endif; ?>




                            </td>
                            <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                            <td>$<?php echo htmlspecialchars($prod['preciofinal']); ?></td>
                            <td>$<?php echo htmlspecialchars($prod['preciovendedor']); ?></td>
                            <td>$<?php echo htmlspecialchars($prod['preciomayorista']); ?></td>
                            <td class="col-detalle">$<?php echo htmlspecialchars($prod['costo']); ?></td>
                            <td class="col-detalle"><?php echo htmlspecialchars($prod['tiempoimpresion']); ?> min</td>
                            <td class="col-detalle"><?php echo htmlspecialchars($prod['tiempopostprocesado']); ?> min</td>
                            <td><?php echo htmlspecialchars($prod['stock']); ?></td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 6px;">
                                    <button type="button" onclick="abrirModalPrestamo('<?php echo $prod['id']; ?>', 'uploads/<?php echo $prod['foto']; ?>', '<?php echo $prod['preciovendedor']; ?>')" style="padding: 6px 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; text-align: center;">Préstamo</button>
                                    <button type="button" onclick="abrirModalPago('<?php echo $prod['id']; ?>', 'uploads/<?php echo $prod['foto']; ?>', '<?php echo $prod['preciovendedor']; ?>')" style="padding: 6px 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Pago</button>
                                    <a href="eliminar.php?id=<?php echo $prod['id']; ?>" onclick="return confirm('¿Estás seguro?');" style="padding: 6px 12px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; text-align: center;">Eliminar</a>
                                    
                                                                    <!-- Cambiar Foto -->
                                 <form action="cambiar_foto.php" method="POST" enctype="multipart/form-data" style="margin: 0;">
                                        <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                        <label style="padding: 6px 12px; background-color: #28a745; color: white; border-radius: 4px; font-size: 14px; text-align: center; cursor: pointer; display: block;">
                                            Cambiar Foto
                                            <input type="file" name="nueva_foto" accept="image/*" required onchange="this.form.submit();" style="display: none;">
                                        </label>
                                    </form>

                                    <!-- Botón Modificar -->
                                    <button type="button" 
                                        onclick="abrirModalModificar(
                                            '<?php echo $prod['id']; ?>', 
                                            '<?php echo addslashes($prod['nombre']); ?>', 
                                            '<?php echo $prod['preciofinal']; ?>', 
                                            '<?php echo $prod['preciovendedor']; ?>', 
                                            '<?php echo $prod['preciomayorista']; ?>', 
                                            '<?php echo $prod['costo']; ?>', 
                                            '<?php echo $prod['stock']; ?>', 
                                            '<?php echo $prod['tiempoimpresion']; ?>', 
                                            '<?php echo $prod['tiempopostprocesado']; ?>'
                                        )" 
                                        style="padding: 6px 12px; background-color: #ffc107; border:none; border-radius: 4px; cursor: pointer; width: 100%;">
                                        Modificar
                                    </button>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" style="text-align: center;">No hay productos registrados aún.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modales -->
    <div id="miModalFoto" class="modal-foto" onclick="cerrarModal()">
        <span class="modal-cerrar" onclick="cerrarModal()">&times;</span>
        <img class="modal-contenido" id="imgAmpliada">
    </div>

    <div id="modalPrestamo" class="modal-foto" style="display:none; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 8px; width: 400px; position: relative;">
            <span onclick="cerrarModalPrestamo()" style="float:right; cursor:pointer; font-size: 20px;">&times;</span>
            <h3>Nuevo Préstamo</h3>
            <img id="modalImgProd" src="" style="width: 100px; display: block; margin: 0 auto 15px;">
            <form action="guardar_prestamo.php" method="POST">
                <input type="hidden" name="idProducto" id="idProducto">
                <label>Vendedor:</label>
                <select name="idVendedor" required style="width:100%; margin-bottom:10px;">
                    <?php $vendedores = $pdo->query("SELECT id, nombre, apellido FROM vendedores")->fetchAll(); foreach($vendedores as $v) echo "<option value='{$v['id']}'>{$v['apellido']} {$v['nombre']}</option>"; ?>
                </select>
                <label>Cantidad:</label> <input type="number" name="cantidad" id="cant" required style="width:100%;" oninput="calcularTotal()">
                <label>Precio Unitario (Base):</label> <input type="text" id="precioBase" readonly style="width:100%; border:none; background:#eee;">
                <label>Precio Total:</label> <input type="text" name="precioTotal" id="precioTotal" readonly style="width:100%; font-weight:bold;">
                <label>Fecha de Préstamo:</label> <input type="date" name="fechaPrestamo" id="fechaPrestamo" required style="width:100%; margin-bottom:10px;">
                <button type="submit" style="margin-top:15px; width:100%;">Confirmar Préstamo</button>
            </form>
        </div>
    </div>

    <div id="modalPago" class="modal-foto" style="display:none; justify-content: center; align-items: center;">
        <div style="background: white; padding: 20px; border-radius: 8px; width: 400px; position: relative;">
            <span onclick="document.getElementById('modalPago').style.display='none'" style="float:right; cursor:pointer; font-size: 20px;">&times;</span>
            <h3>Registrar Pago</h3>
            <img id="imgPago" src="" style="width: 80px; display: block; margin: 0 auto 15px;">
            <form action="guardar_pago.php" method="POST">
                <input type="hidden" name="idProducto" id="idP">
                <input type="hidden" id="precioUnitario"> 
                <label>Vendedor:</label>
                <select name="idVendedor" required style="width:100%; margin-bottom:10px;">
                    <?php $vendedores = $pdo->query("SELECT id, nombre, apellido FROM vendedores")->fetchAll(); foreach($vendedores as $v) echo "<option value='{$v['id']}'>{$v['apellido']} {$v['nombre']}</option>"; ?>
                </select>
                <label>Cantidad:</label> <input type="number" name="cantidad" id="cantPago" required style="width:100%;" oninput="calcularPago()">
                <label>Monto:</label> <input type="text" name="monto" id="montoPago" readonly style="width:100%; font-weight:bold; background:#eee;">
                <label>Tipo Pago:</label>
                <select name="tipoPago" style="width:100%; margin-bottom:10px;">
                    <option value="1">Transferencia</option><option value="2">Efectivo</option>
                </select>
                <label>Fecha:</label> <input type="date" name="fecha" id="fechaPago" required style="width:100%; margin-bottom:10px;">
                <button type="submit" style="width:100%; padding:10px; background:#28a745; color:white; border:none;">Guardar Pago</button>
            </form>
        </div>
    </div>

    <!-- MODIFICAR -->
<div id="modalModificar" class="modal-foto" style="display:none; justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; border-radius: 8px; width: 400px; max-height: 90vh; overflow-y: auto; position: relative;">
        <span onclick="document.getElementById('modalModificar').style.display='none'" style="float:right; cursor:pointer; font-size: 20px;">&times;</span>
        <h3>Modificar Producto</h3>
        <form action="modificar.php" method="POST">
            <input type="hidden" name="id" id="modId">
            <label>Nombre:</label> <input type="text" name="nombre" id="modNombre" required>
            <label>Precio Final:</label> <input type="text" name="preciofinal" id="modPrecioFinal">
            <label>Precio Vendedor:</label> <input type="text" name="preciovendedor" id="modPrecioVendedor">
            <label>Precio Mayorista:</label> <input type="text" name="preciomayorista" id="modPrecioMayorista">
            <label>Costo:</label> <input type="text" name="costo" id="modCosto">
            <label>Stock:</label> <input type="number" name="stock" id="modStock">
            <label>Tiempo Impresión:</label> <input type="number" name="tiempoimpresion" id="modTiempoImp">
            <label>Tiempo Post-Procesado:</label> <input type="number" name="tiempopostprocesado" id="modTiempoPost">
            
            <button type="submit" style="background:#ffc107; border:none; padding:10px; width:100%; margin-top:15px; cursor:pointer;">Guardar Cambios</button>
        </form>
    </div>
</div>
    <!-- FIN MODIFICAR -->


    <script src="main.js"></script>
</body>
</html>