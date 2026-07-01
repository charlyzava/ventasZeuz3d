<?php
// Incluimos la conexión para poder consultar los productos
require 'conexion.php';

try {
    // Consultamos los últimos 20 registros ordenados por ID descendente
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
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* Para la imagen ampliada*/
        /* El fondo oscuro que cubre toda la pantalla */
.modal-foto {
    display: none; 
    position: fixed; 
    z-index: 9999; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.8); /* Fondo negro semitransparente */
    align-items: center;
    justify-content: center;
}

/* La imagen ampliada en el centro */
.modal-contenido {
    max-width: 90%;
    max-height: 85%;
    border-radius: 4px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.5);
}

/* El botón de cerrar (X) */
.modal-cerrar {
    position: absolute;
    top: 20px;
    right: 30px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    user-select: none;
}


        /* Estilos básicos para que la tabla se vea ordenada */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .img-tabla {
            width: 70px;
            height: 70px;
            object-fit: cover; /* Evita que la imagen se deforme al forzar los 70x70 */
            border-radius: 4px;
        }

    /* Clase para ocultar las columnas por defecto */
    .col-detalle {
        display: none;
    }



    </style>
</head>
<body>
    <h1>Productos</h1>
    <form action="guardar.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <input type="text" name="preciofinal" placeholder="Precio Final"><br>
        <input type="text" name="preciovendedor" placeholder="Precio Vendedor"><br>
        <input type="text" name="preciomayorista" placeholder="Precio Mayorista"><br>
        <input type="text" name="costo" placeholder="Costo"><br>
        <input type="number" name="tiempoimpresion" placeholder="Tiempo Impresión"><br>
        <input type="number" name="tiempopostprocesado" placeholder="Tiempo Post-Procesado"><br>
        <input type="file" name="foto" accept="image/*" required><br>
        <button type="submit">Guardar Producto</button>
    </form>

    <div id="mensaje"></div>

    <h2>Últimos 20 registros</h2>


<div style="margin: 20px 0;">
    <input type="text" id="buscador" placeholder="Buscar por nombre..." 
           onkeyup="filtrarTabla()" 
           style="padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
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
            <th class="col-detalle">Costo</th> <th class="col-detalle">T. Impresión</th> <th class="col-detalle">T. Post-Procesado</th> <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($productos) > 0): ?>
            <?php foreach ($productos as $prod): ?>
                <tr>


<td>
                <?php if (!empty($prod['foto']) && file_exists('uploads/' . $prod['foto'])): ?>
        <img src="uploads/<?php echo $prod['foto']; ?>" 
             alt="Foto" 
             class="img-tabla" 
             style="cursor: pointer;" 
             onclick="abrirModal(this.src)">
    <?php else: ?>
        <div style="width: 70px; height: 70px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #777;">Sin foto</div>
    <?php endif; ?>
</td>



    <!-- 
                    <td>
                        <?php if (!empty($prod['foto']) && file_exists('uploads/' . $prod['foto'])): ?>
                            <img src="uploads/<?php echo $prod['foto']; ?>" alt="Foto" class="img-tabla">
                        <?php else: ?>
                            <div style="width: 70px; height: 70px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #777;">Sin foto</div>
                        <?php endif; ?>
                    </td>-->
                    <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                    <td>$<?php echo htmlspecialchars($prod['preciofinal']); ?></td>
                    <td>$<?php echo htmlspecialchars($prod['preciovendedor']); ?></td>
                    <td>$<?php echo htmlspecialchars($prod['preciomayorista']); ?></td>
                    
                    <td class="col-detalle">$<?php echo htmlspecialchars($prod['costo']); ?></td>
                    <td class="col-detalle"><?php echo htmlspecialchars($prod['tiempoimpresion']); ?> min</td>
                    <td class="col-detalle"><?php echo htmlspecialchars($prod['tiempopostprocesado']); ?> min</td>
                    
                    <td>

                    
                        <div style="display: flex; flex-direction: column; gap: 6px;">
        <button type="button" 
                onclick="abrirModalPrestamo('<?php echo $prod['id']; ?>', 'uploads/<?php echo $prod['foto']; ?>', '<?php echo $prod['preciovendedor']; ?>')"
                style="padding: 6px 12px; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; text-align: center;">
            Préstamo
        </button>


        <button type="button" 
        onclick="abrirModalPago('<?php echo $prod['id']; ?>', 'uploads/<?php echo $prod['foto']; ?>', '<?php echo $prod['preciovendedor']; ?>')"
        style="padding: 6px 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
        Pago
        </button>

        <a href="eliminar.php?id=<?php echo $prod['id']; ?>" 
           onclick="return confirm('¿Estás seguro?');" 
           style="padding: 6px 12px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; text-align: center;">
           Eliminar
        </a>

        <form action="cambiar_foto.php" method="POST" enctype="multipart/form-data" style="margin: 0;">
            <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
            <label style="padding: 6px 12px; background-color: #28a745; color: white; border-radius: 4px; font-size: 14px; text-align: center; cursor: pointer; display: block;">
                Cambiar Foto
                <input type="file" name="nueva_foto" accept="image/*" required onchange="this.form.submit();" style="display: none;">
            </label>
        </form>

    </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" style="text-align: center;">No hay productos registrados aún.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<script>
function alternarColumnas() {
    // Detectamos si el checkbox está marcado o no
    const checkbox = document.getElementById('chkMostrarDetalles');
    // Buscamos todos los elementos (th y td) que tengan la clase 'col-detalle'
    const columnas = document.querySelectorAll('.col-detalle');
    
    // Si está marcado usamos 'table-cell' (el display correcto para celdas), si no, 'none'
    const displayValue = checkbox.checked ? 'table-cell' : 'none';
    
    columnas.forEach(columna => {
        columna.style.display = displayValue;
    });
}
</script>

<script>
function abrirModal(rutaImagen) {
    const modal = document.getElementById('miModalFoto');
    const imgAmpliada = document.getElementById('imgAmpliada');
    
    imgAmpliada.src = rutaImagen; // Asigna la foto en la que se hizo clic
    modal.style.display = 'flex'; // Muestra la ventana usando flexbox para centrarla
}

function cerrarModal() {
    const modal = document.getElementById('miModalFoto');
    modal.style.display = 'none'; // Oculta la ventana
}
</script>

<script>
function filtrarTabla() {
    // Obtenemos el valor del input y lo pasamos a minúsculas para comparar mejor
    const filtro = document.getElementById('buscador').value.toLowerCase();
    const tabla = document.querySelector('table');
    const filas = tabla.getElementsByTagName('tr');

    // Empezamos desde 1 para saltar la cabecera (thead)
    for (let i = 1; i < filas.length; i++) {
        const celdaNombre = filas[i].getElementsByTagName('td')[1]; // La columna 1 es 'Nombre'
        
        if (celdaNombre) {
            const texto = celdaNombre.textContent || celdaNombre.innerText;
            // Si el nombre contiene el texto buscado, se muestra, si no, se oculta
            if (texto.toLowerCase().indexOf(filtro) > -1) {
                filas[i].style.display = "";
            } else {
                filas[i].style.display = "none";
            }
        }
    }
}
</script>

<script>
function abrirModalPrestamo(id, src, precio) {
    document.getElementById('idProducto').value = id;
    document.getElementById('modalImgProd').src = src;
    document.getElementById('precioBase').value = precio;
    // Configurar la fecha actual en formato YYYY-MM-DD
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fechaPrestamo').value = hoy;
    document.getElementById('modalPrestamo').style.display = 'flex';
}

function cerrarModalPrestamo() {
    document.getElementById('modalPrestamo').style.display = 'none';
}
function calcularTotal() {
    let cant = document.getElementById('cant').value;
    let base = document.getElementById('precioBase').value;
    
    // Si la cantidad está vacía, el total debe ser 0 o vacío
    if (cant === "") {
        document.getElementById('precioTotal').value = "";
    } else {
        // Multiplicamos y asignamos al input correcto (precioTotal)
        document.getElementById('precioTotal').value = (parseFloat(cant) * parseFloat(base)).toFixed(2);
    }
}

</script>
<script>
    function abrirModalPago(id, src, precio) {
    document.getElementById('idP').value = id;
    document.getElementById('imgPago').src = src;
    document.getElementById('precioUnitario').value = precio;
    document.getElementById('fechaPago').value = new Date().toISOString().split('T')[0];
    document.getElementById('modalPago').style.display = 'flex';
}

function calcularPago() {
    let cant = document.getElementById('cantPago').value;
    let unit = document.getElementById('precioUnitario').value;
    document.getElementById('montoPago').value = (cant * unit).toFixed(2);
}
</script>

<div id="miModalFoto" class="modal-foto" onclick="cerrarModal()">
    <span class="modal-cerrar" onclick="cerrarModal()">&times;</span>
    <img class="modal-contenido" id="imgAmpliada">
</div>


        <!-- 2da Ventana Modal -->
<div id="modalPrestamo" class="modal-foto" style="display:none; justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; border-radius: 8px; width: 400px; position: relative;">
        <span onclick="cerrarModalPrestamo()" style="float:right; cursor:pointer; font-size: 20px;">&times;</span>
        <h3>Nuevo Préstamo</h3>
        <img id="modalImgProd" src="" style="width: 100px; display: block; margin: 0 auto 15px;">
        
        <form action="guardar_prestamo.php" method="POST">
            <input type="hidden" name="idProducto" id="idProducto">
            <label>Vendedor:</label>
            <select name="idVendedor" required style="width:100%; margin-bottom:10px;">
                <?php
                $vendedores = $pdo->query("SELECT id, nombre, apellido FROM vendedores")->fetchAll();
                foreach($vendedores as $v) echo "<option value='{$v['id']}'>{$v['apellido']} {$v['nombre']}</option>";
                ?>
            </select>
            <label>Cantidad:</label>
            <input type="number" name="cantidad" id="cant" required style="width:100%;" oninput="calcularTotal()">
            <label>Precio Unitario (Base):</label>
            <input type="text" id="precioBase" readonly style="width:100%; border:none; background:#eee;">
            <label>Precio Total:</label>
            <input type="text" name="precioTotal" id="precioTotal" readonly style="width:100%; font-weight:bold;">

            <label>Fecha de Préstamo:</label>
            <input type="date" name="fechaPrestamo" id="fechaPrestamo" required style="width:100%; margin-bottom:10px;">

            <button type="submit" style="margin-top:15px; width:100%;">Confirmar Préstamo</button>
        </form>
    </div>
</div>

<!-- Ventana Pagos -->

<div id="modalPago" class="modal-foto" style="display:none; justify-content: center; align-items: center;">
    <div style="background: white; padding: 20px; border-radius: 8px; width: 400px; position: relative;">
        <span onclick="document.getElementById('modalPago').style.display='none'" style="float:right; cursor:pointer; font-size: 20px;">&times;</span>
        <h3>Registrar Pago</h3>
        <img id="imgPago" src="" style="width: 80px; display: block; margin: 0 auto 15px;">
        
        <form action="guardar_pago.php" method="POST">
            <input type="hidden" name="idProducto" id="idP">
            <input type="hidden" id="precioUnitario"> <label>Vendedor:</label>
            <select name="idVendedor" required style="width:100%; margin-bottom:10px;">
                <?php
                $vendedores = $pdo->query("SELECT id, nombre, apellido FROM vendedores")->fetchAll();
                foreach($vendedores as $v) echo "<option value='{$v['id']}'>{$v['apellido']} {$v['nombre']}</option>";
                ?>
            </select>
            
            <label>Cantidad:</label>
            <input type="number" name="cantidad" id="cantPago" required style="width:100%;" oninput="calcularPago()">
            
            <label>Monto:</label>
            <input type="text" name="monto" id="montoPago" readonly style="width:100%; font-weight:bold; background:#eee;">
            
            <label>Tipo Pago:</label>
            <select name="tipoPago" style="width:100%; margin-bottom:10px;">
                <option value="1">Transferencia</option>
                <option value="2">Efectivo</option>
            </select>
            
            <label>Fecha:</label>
            <input type="date" name="fecha" id="fechaPago" required style="width:100%; margin-bottom:10px;">
            
            <button type="submit" style="width:100%; padding:10px; background:#28a745; color:white; border:none;">Guardar Pago</button>
        </form>
    </div>
</div>
        <!-- Fin Pagos -->
</body>
</html>