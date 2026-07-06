function toggleDetalle(id) {
    const checkbox = document.getElementById(id);
    const tabla = document.getElementById('detalle_' + id);
    tabla.style.display = checkbox.checked ? 'block' : 'none';
}

function mostrarDatos() {
    const select = document.getElementById('listaVendedores');
    const detalles = document.getElementById('detalles');
    const tablaDiv = document.getElementById('tablaPrestamos');
    
    if (select.value === "") {
        detalles.style.display = 'none';
        tablaDiv.innerHTML = '';
        return;
    }

    const opcion = select.options[select.selectedIndex];
    document.getElementById('verDni').innerText = opcion.getAttribute('data-dni');
    document.getElementById('verCelular').innerText = opcion.getAttribute('data-celular');
    detalles.style.display = 'block';

    fetch('obtener_prestamos.php?idVendedor=' + select.value)
        .then(response => response.text())
        .then(html => {
            tablaDiv.innerHTML = html;
        });
}

function eliminarRegistro(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este registro?')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('eliminar_prestamo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Registro eliminado');
                // Refrescamos la lista del vendedor automáticamente
                mostrarDatos(); 
            } else {
                alert('Error al eliminar');
            }
        });
    }
}