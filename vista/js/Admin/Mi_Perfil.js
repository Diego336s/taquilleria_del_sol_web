document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del usuario en la UI (ej. saludo en dashboard)
    populateUserData();

    // Event Listener para REGISTRO (formulario actualizar mi perfil)
    const update_Perfil = document.getElementById('form_actualizar_perfil');
    if (update_Perfil) {
        update_Perfil.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrupdatePerfil();
        });
    }


});


async function ctrupdatePerfil() {

    // 1. Obtener el token y los datos del usuario de la sesión
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión. Por favor, inicia sesión de nuevo.');
        return;
    }

    // 2. Recolectar los datos del formulario
    const datos = {
        nombre: document.getElementById('profile_nombre')?.value.trim(),
        apellido: document.getElementById('profile_apellido')?.value.trim(),
        telefono: document.getElementById('profile_telefono')?.value.trim(),
        sexo: document.getElementById('profile_sexo')?.value,
    };

    // Validación simple de campos
    if (!datos.nombre || !datos.apellido || !datos.telefono || !datos.sexo) {
        mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos requeridos.');
        return;
    }

    // 3. Mostrar alerta de carga
    Swal.fire({
        title: 'Actualizando perfil...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // 4. Realizar la petición a la API
    const userData = JSON.parse(userDataString);
    const urlAPI = `${ApiConexion}actualizarCliente/${userData.id}`;


    try {
        const respuesta = await fetch(urlAPI, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(datos)
        });

        const data = await respuesta.json();
        Swal.close();

        if (data.success === true) {
            // 5. Actualizar sessionStorage con los nuevos datos
            const updatedUserData = data.cliente || data.user;
            if (updatedUserData) {
                sessionStorage.setItem('userData', JSON.stringify(updatedUserData));
                // Refrescar el nombre en el saludo del dashboard si existe
                populateUserData();
            }

            Swal.fire({
                icon: 'success',
                title: '¡Perfil Actualizado!',
                text: data.message || 'Tus datos se han guardado correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            //recargar la pagina despues de haber actualiza exitosamente
            setTimeout(()=>{
                window.location.reload();
            },2000);
            
        } else {
            mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudieron guardar los cambios.');
        }
    } catch (error) {
        Swal.close();
        console.error("Error al actualizar perfil:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}
