window.onload = () =>{
// controlador.js
const form = document.getElementById('prodForm');

// Verificamos si el formulario existe antes de añadir el evento
if (form) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const res = await fetch('guardar.php', { method: 'POST', body: formData });
        const mensaje = await res.text();
        
        const divMensaje = document.getElementById('mensaje');
        if (divMensaje) divMensaje.innerText = mensaje;
        else alert(mensaje);
        
        if (mensaje.includes("Éxito")) form.reset();
    });
} else {
    console.error("Error: No se encontró el elemento con ID 'prodForm'");
}


}



/*document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('prodForm');

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault(); // Evita la recarga

            const formData = new FormData(form);

            try {
                const response = await fetch('guardar.php', {
                    method: 'POST',
                    body: formData
                });

                const resultado = await response.text();
                
                // Si tienes un elemento para mostrar mensajes, úsalo:
                const mensajeDiv = document.getElementById('mensaje');
                if (mensajeDiv) {
                    mensajeDiv.innerText = resultado;
                } else {
                    // Si no, usamos alert
                    alert(resultado);
                }

                // Limpiar formulario solo si el servidor respondió con éxito
                if (resultado.includes("Éxito")) {
                    form.reset();
                }
            } catch (error) {
                console.error("Error al enviar:", error);
                alert("Hubo un error de conexión con el servidor.");
            }
        });
    }
});*/