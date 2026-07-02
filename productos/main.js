/* --- GESTIÓN DE MODALES --- */
function abrirModal(rutaImagen) {
    const modal = document.getElementById('miModalFoto');
    document.getElementById('imgAmpliada').src = rutaImagen;
    modal.style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('miModalFoto').style.display = 'none';
}

function abrirModalPrestamo(id, src, precio) {
    document.getElementById('idProducto').value = id;
    document.getElementById('modalImgProd').src = src;
    document.getElementById('precioBase').value = precio;
    document.getElementById('fechaPrestamo').value = new Date().toISOString().split('T')[0];
    document.getElementById('modalPrestamo').style.display = 'flex';
}

function cerrarModalPrestamo() {
    document.getElementById('modalPrestamo').style.display = 'none';
}

function abrirModalPago(id, src, precio) {
    document.getElementById('idP').value = id;
    document.getElementById('imgPago').src = src;
    document.getElementById('precioUnitario').value = precio;
    document.getElementById('fechaPago').value = new Date().toISOString().split('T')[0];
    document.getElementById('modalPago').style.display = 'flex';
}

function abrirModalModificar(id, nombre, pFinal, pVend, pMay, costo, stock, tImp, tPost) {
    document.getElementById('modId').value = id;
    document.getElementById('modNombre').value = nombre;
    document.getElementById('modPrecioFinal').value = pFinal;
    document.getElementById('modPrecioVendedor').value = pVend;
    document.getElementById('modPrecioMayorista').value = pMay;
    document.getElementById('modCosto').value = costo;
    document.getElementById('modStock').value = stock;
    document.getElementById('modTiempoImp').value = tImp;
    document.getElementById('modTiempoPost').value = tPost;
    
    document.getElementById('modalModificar').style.display = 'flex';
}

/* --- CÁLCULOS --- */
function calcularTotal() {
    let cant = document.getElementById('cant').value;
    let base = document.getElementById('precioBase').value;
    document.getElementById('precioTotal').value = cant ? (parseFloat(cant) * parseFloat(base)).toFixed(2) : "";
}

function calcularPago() {
    let cant = document.getElementById('cantPago').value;
    let unit = document.getElementById('precioUnitario').value;
    document.getElementById('montoPago').value = cant ? (parseFloat(cant) * parseFloat(unit)).toFixed(2) : "";
}

/* --- UTILIDADES --- */
function alternarColumnas() {
    const checkbox = document.getElementById('chkMostrarDetalles');
    const columnas = document.querySelectorAll('.col-detalle');
    columnas.forEach(col => col.style.display = checkbox.checked ? 'table-cell' : 'none');
}

function filtrarTabla() {
    const filtro = document.getElementById('buscador').value.toLowerCase();
    const filas = document.querySelectorAll('table tbody tr');
    filas.forEach(fila => {
        const nombre = fila.getElementsByTagName('td')[1].textContent.toLowerCase();
        fila.style.display = nombre.includes(filtro) ? "" : "none";
    });
}