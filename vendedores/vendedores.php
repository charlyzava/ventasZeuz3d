<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Vendedores</title>
</head>
<body>
    <a href="../index.php">Home</a>
    <h1>Vendedores</h1>
    
    <label for="listaVendedores">Seleccionar Vendedor:</label>
    <select id="listaVendedores" onchange="mostrarDatos()">
        <option value="">-- Seleccione un vendedor --</option>
        <?php
        // 1. Mostrar errores de PHP para debug
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        try {
            // 2. Intentar cargar la conexión
            require '../conexion.php';

            // 3. Consultar a la base de datos
            $stmt = $pdo->query("SELECT id, Apellido, Nombre, dni, NroCelular FROM vendedores ORDER BY Apellido ASC");
            $vendedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 4. Verificar si hay registros
            if (count($vendedores) > 0) {
                foreach ($vendedores as $v) {
                    echo "<option value='{$v['id']}' 
                            data-dni='{$v['dni']}' 
                            data-nombre='{$v['Nombre']}' 
                            data-apellido='{$v['Apellido']}' 
                            data-celular='{$v['NroCelular']}'>
                            " . htmlspecialchars($v['Apellido'] . ', ' . $v['Nombre']) . "
                          </option>";
                }
            } else {
                echo "<option>No hay vendedores registrados</option>";
            }

        } catch (Exception $e) {
            // 5. Si algo falla, lo veremos aquí en pantalla
            echo "<option>Error: " . $e->getMessage() . "</option>";
        }
        ?>
    </select>

    <!-- El resto de tu código JS se mantiene igual -->
    <div id="detalles" style="margin-top: 20px; padding: 15px; border: 1px solid #ccc; width: 300px; display: none;">
        <h3>Datos del Vendedor</h3>
        <p><strong>DNI:</strong> <span id="verDni"></span></p>
        <p><strong>Celular:</strong> <span id="verCelular"></span></p>
    </div>
<!-- Agrega este div donde quieras que aparezca la tabla -->
<div id="tablaPrestamos" style="margin-top: 30px;"></div>


<script>
function mostrarDatos() {
    const select = document.getElementById('listaVendedores');
    const detalles = document.getElementById('detalles');
    const tablaDiv = document.getElementById('tablaPrestamos');
    
    if (select.value === "") {
        detalles.style.display = 'none';
        tablaDiv.innerHTML = '';
        return;
    }

    // 1. Mostrar datos básicos (igual que antes)
    const opcion = select.options[select.selectedIndex];
    document.getElementById('verDni').innerText = opcion.getAttribute('data-dni');
    document.getElementById('verCelular').innerText = opcion.getAttribute('data-celular');
    detalles.style.display = 'block';

    // 2. Cargar tabla de préstamos vía AJAX
    fetch('obtener_prestamos.php?idVendedor=' + select.value)
        .then(response => response.text())
        .then(html => {
            tablaDiv.innerHTML = html;
        });
}
</script>
    
</body>
</html>