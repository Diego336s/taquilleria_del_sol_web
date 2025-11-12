document.addEventListener('DOMContentLoaded', () => {


    const update_Empresa = document.getElementById('form_actualizar_perfil_empresa');
    if (update_Empresa) {
        update_Empresa.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrupdatePerfilEmpresa();
        });
    }

    // =========================================================================
    // FUNCIÓN: ACTUALIZAR PERFIL EMPRESA
    // =========================================================================
    async function ctrupdatePerfilEmpresa() {

        const token = sessionStorage.getItem('userToken');
        const userDataString = sessionStorage.getItem('userData');

        if (!token || !userDataString) {
            mostrarAlerta('error', 'Sesión inválida', 'Por favor inicia sesión nuevamente.');
            return;
        }

        const datos = {
            nombre_empresa: document.getElementById('profile_nombre').value.trim(),
            nit: document.getElementById('profile_nit').value.trim(),
            representante_legal: document.getElementById('profile_representante_legal').value.trim(),
            documento_representante: document.getElementById('profile_documento_representante').value.trim(),
            nombre_contacto: document.getElementById('profile_nombre_contacto').value.trim(),
            telefono: document.getElementById('profile_telefono').value.trim(),
            correo: document.getElementById('profile_correo').value.trim()
        };

        if (Object.values(datos).some(valor => !valor)) {
            mostrarAlerta('error', 'Campos incompletos', 'Por favor rellena todos los campos requeridos.');
            return;
        }

        Swal.fire({
            title: 'Actualizando perfil...',
            text: 'Por favor, espera un momento.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const userData = JSON.parse(userDataString);
        const urlAPI = `${ApiConexion}actualizarEmpresa/${userData.id}`;

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
                // Actualizar sessionStorage con los nuevos datos
                const updatedUserData = data.empresa || data.user;
                if (updatedUserData) {
                    sessionStorage.setItem('userData', JSON.stringify(updatedUserData));
                }

                Swal.fire({
                    icon: 'success',
                    title: '¡Perfil Actualizado!',
                    text: data.message || 'La información se ha guardado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudieron guardar los cambios.');
            }

        } catch (error) {
            Swal.close();
            console.error("Error al actualizar perfil de empresa:", error);
            mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
        }
    }


});
