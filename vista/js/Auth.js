// Este código debe ir en vista/js/main.js (o al final de vista/modulos/zpie.php)

document.addEventListener('DOMContentLoaded', () => {
    // Asegúrate de que el formulario en registrarUsuario.php tenga el id="formulario-registro"
    const form = document.getElementById('formulario-registro'); 
    
    // 🚨 Este bloque es el que soluciona el problema de que 'no registra' al interceptar el submit.
    if (form) {
        form.addEventListener('submit', async function(event) {
            // 1. Prevenir el envío tradicional del formulario y la recarga de página
            event.preventDefault(); 
            
            // 2. Llamar a su función de control de registro
            await ctrRegistroUsuario(); // Llamamos sin pasar 'form' ya que usamos getElementById
        });
    }
});


/**
 * Función principal para manejar el envío del formulario
 */
async function ctrRegistroUsuario() {

    // 1. Recolección de datos del formulario usando .value de cada ID
    const datos = {
        // Los IDs deben coincidir con los IDs del formulario HTML (registrarUsuario.php)
        nombre: document.getElementById('id_Nombre').value, // El formulario usa id="id_Nombre"
        apellido: document.getElementById('id_Apellido').value, // El formulario usa id="id_Apellido"
        sexo: document.getElementById('id_Sexo').value,
        documento: document.getElementById('id_Documento').value, // El formulario usa id="id_Documento"
        fecha_nacimiento: document.getElementById('id_fecha_Nacimiento').value, // El formulario usa id="id_fecha_Nacimiento"
        telefono: document.getElementById('id_Telefono').value, // El formulario usa id="id_Telefono"
        correo: document.getElementById('id_Email').value,
        
        // Claves para el backend Laravel:
        clave: document.getElementById('id_Clave').value, 
        confirmar_clave: document.getElementById('id_ClaveConfirm').value
    };

    // 2. Validar longitud mínima de la clave (local)
    if (datos.clave.length < 6 || datos.confirmar_clave.length < 6) { 
        mostrarAlerta('error', 'Error', 'Las contraseñas deben ser de mínimo 6 caracteres.');
        return;
    }

    // 3. Validar que las claves coincidan localmente
    if (datos.clave !== datos.confirmar_clave) {
        mostrarAlerta('error', 'Error', 'Las contraseñas no coinciden.');
        return;
    }

    Swal.fire({
        title: 'Procesando registro...',
        text: 'Por favor, espere un momento.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading(); 
        }
    });
    
    // 4. Endpoint de la API
    const urlAPI = "http://127.0.0.1:8000/api/registrar/cliente";

    // 5. Configuración de la solicitud (POST)
    try {
        const respuesta = await fetch(urlAPI, {
            method: 'POST',
            headers: {
                // El backend Laravel acepta application/json
                'Content-Type': 'application/json',
            },
            // Convertimos el objeto de datos a JSON para enviarlo
            body: JSON.stringify(datos)
        });

        // 6. Lectura del cuerpo de la respuesta JSON
        const data = await respuesta.json();

        Swal.close();

        // 7. Manejo de la Respuesta: Éxito (201) vs. Error (400, 422, etc.)
        if (respuesta.status === 200) { 
            mostrarAlerta('success', '¡Registro Exitoso!', data.message || 'Ahora puedes iniciar sesión.');

            setTimeout(() => {
                 window.location.href = "login"; 
            }, 1500); 
           
        } else if (respuesta.status >= 400 && respuesta.status < 500) {
            
            let mensajeDetallado = data.message || 'Error desconocido en el registro.';
            
            if (data.errors && typeof data.errors === 'object') {
                // Maneja errores de validación (generalmente 422 Unprocessable Entity)
                mensajeDetallado = formatValidationErrors(data.errors); 
            }
            else if (data.message) {
                 mensajeDetallado = data.message;
            }

            console.error('Error del cliente:', data);
            mostrarAlerta('error', 'Error al registrar', mensajeDetallado);

        } else {
            console.error('Error inesperado del servidor:', respuesta.status, data);
            mostrarAlerta('error', 'Error al registrar', 'Ocurrió un error inesperado del servidor. (Código: ' + respuesta.status + ')');
        }

    } catch (error) {
        // 🚨 Error de red o CORS
        console.error('Error de red o conexión:', error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor API. Verifique que la URL y el puerto (http://127.0.0.1:8000) sean correctos y que el servidor de Laravel esté corriendo.');
    }
}


function mostrarAlerta(icon, title, text) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: icon,
            title: title,
            html: text, 
            showConfirmButton: true,
            confirmButtonText: "Aceptar"
        });
    } else {
        alert(`${title} (${icon}): ${text.replace(/<br>/g, '\n')}`);
    }
}

function formatValidationErrors(errors) {
    let html = '';
    for (const field in errors) {
        errors[field].forEach(error => {
            html += `• ${error}<br>`;
        });
    }
    return html;
}