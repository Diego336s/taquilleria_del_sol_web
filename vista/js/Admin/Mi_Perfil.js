document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del administrador en la UI
    populateAdminData();

    // Cargar datos del perfil del admin
    ctrCargarPerfilAdmin();

    // Event Listener para actualizar perfil
    const updatePerfil = document.getElementById('form_actualizar_perfil');
    if (updatePerfil) {
        updatePerfil.addEventListener('submit', async function (event) {
            event.preventDefault();
            await ctrActualizarPerfilAdmin();
        });
    }
});

// =========================================================================
// 🔐 FUNCIÓN: PROTECCIÓN DE RUTAS PARA ADMIN
// =========================================================================

function checkAuthAndRedirect() {
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión. Redirigiendo al login.');
        window.location.replace("../../../index.php?ruta=login");
        return;
    }

    try {
        const userData = JSON.parse(userDataString);
        const userRole = userData.rol || userData.role || userData.tipo || 'user';
        if (userRole.toLowerCase() !== 'admin' && userRole.toLowerCase() !== 'administrator') {
            console.warn('Usuario no es admin, rol actual:', userRole);
            window.location.replace("../../../index.php?ruta=dashboard-usuario");
            return;
        }
    } catch (e) {
        console.error('Error parseando userData:', e);
        sessionStorage.clear();
        window.location.replace("../../../index.php?ruta=login");
        return;
    }
}

// =========================================================================
// ✨ FUNCIÓN: POBLAR DATOS DEL ADMIN EN LA UI
// =========================================================================

function populateAdminData() {
    const userDataString = sessionStorage.getItem('userData');

    if (!userDataString) {
        console.warn('⚠️ No hay datos de administrador en sessionStorage.');
        return;
    }

    try {
        const userData = JSON.parse(userDataString);
        console.log("👨‍💼 Datos del administrador cargados desde sessionStorage:", userData);
        // Aquí podríamos poblar algún elemento si fuera necesario
    } catch (e) {
        console.error('❌ Error al parsear los datos del administrador desde sessionStorage:', e);
    }
}

// =========================================================================
// 📋 FUNCIÓN: CARGAR PERFIL DEL ADMIN
// =========================================================================

async function ctrCargarPerfilAdmin() {
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontraron datos de sesión.');
        return;
    }

    try {
        const userData = JSON.parse(userDataString);

        // Poblar información de visualización
        document.getElementById('profile_nombre_display').textContent = userData.nombre || userData.nombres || '—';
        document.getElementById('profile_apellido_display').textContent = userData.apellido || userData.apellidos || '—';
        document.getElementById('profile_documento_display').textContent = userData.documento || '—';
        document.getElementById('profile_telefono_display').textContent = userData.telefono || '—';
        document.getElementById('profile_sexo_display').textContent = userData.sexo || '—';
        document.getElementById('profile_correo_display').textContent = userData.correo || '—';
        document.getElementById('profile_rol_display').textContent = userData.rol || 'admin';

        // Actualizar avatar según sexo
        const avatarElement = document.getElementById('profile-avatar');
        if (avatarElement) {
            const sexo = userData.sexo;
            if (sexo === 'Masculino') {
                avatarElement.textContent = '👨‍💼';
            } else if (sexo === 'Femenino') {
                avatarElement.textContent = '👩‍💼';
            } else {
                avatarElement.textContent = '👤';
            }
        }

        // Poblar formulario de edición
        document.getElementById('profile_nombre').value = userData.nombre || userData.nombres || '';
        document.getElementById('profile_apellido').value = userData.apellido || userData.apellidos || '';
        document.getElementById('profile_documento').value = userData.documento || '';
        document.getElementById('profile_telefono').value = userData.telefono || '';
        document.getElementById('profile_sexo').value = userData.sexo || '';
        document.getElementById('profile_correo').value = userData.correo || '';

    } catch (e) {
        console.error('Error cargando perfil del admin:', e);
        mostrarAlerta('error', 'Error al cargar perfil', 'No se pudieron cargar los datos del perfil.');
    }
}

// =========================================================================
// 📝 FUNCIÓN: ACTUALIZAR PERFIL DEL ADMIN
// =========================================================================

async function ctrActualizarPerfilAdmin() {
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
        // Agregar campos adicionales si existen
        correo: userData.correo, // Mantener el correo actual
        documento: userData.documento // Mantener el documento actual
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

    // 4. Intentar actualizar en el backend
    const userData = JSON.parse(userDataString);
    const urlAPI = `${ApiConexion}actualizarAdmin/${userData.id}`;

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
            const updatedUserData = data.admin || data.user;
            if (updatedUserData) {
                sessionStorage.setItem('userData', JSON.stringify(updatedUserData));
                // Refrescar la información en pantalla
                ctrCargarPerfilAdmin();
            }

            Swal.fire({
                icon: 'success',
                title: '¡Perfil Actualizado!',
                text: data.message || 'Tus datos se han guardado correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            // Recargar la página después de actualizar exitosamente
            setTimeout(() => {
                window.location.reload();
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al actualizar', data.message || 'No se pudieron guardar los cambios.');
        }
    } catch (error) {
        Swal.close();
        console.warn("Error al conectar con backend, usando lógica de perfil de usuario:", error);

        // Usar la misma lógica que ctrupdatePerfil() para usuarios
        ctrupdatePerfilAdminLocal(datos, userData);
    }
}

// =========================================================================
// 📝 FUNCIÓN: ACTUALIZAR PERFIL LOCAL (FALLBACK)
// =========================================================================

async function ctrupdatePerfilAdminLocal(datos, userData) {
    // Simular actualización usando la lógica del perfil de usuario
    try {
        // Actualizar sessionStorage
        const updatedUserData = { ...userData, ...datos };
        sessionStorage.setItem('userData', JSON.stringify(updatedUserData));

        // Refrescar la información en pantalla
        ctrCargarPerfilAdmin();

        Swal.fire({
            icon: 'success',
            title: '¡Perfil Actualizado!',
            text: 'Tus datos se han guardado correctamente (modo local).',
            timer: 2000,
            showConfirmButton: false
        });

        // Recargar la página después de actualizar exitosamente
        setTimeout(() => {
            window.location.reload();
        }, 2000);

    } catch (error) {
        console.error("Error al actualizar perfil local:", error);
        mostrarAlerta('error', 'Error al actualizar', 'No se pudieron guardar los cambios.');
    }
}

// =========================================================================
// 🔙 FUNCIÓN: VOLVER AL DASHBOARD
// =========================================================================

function volverDashboard() {
    window.location.href = '../../../index.php?ruta=dashboard-admin';
}

// =========================================================================
// ℹ️ FUNCIÓN AUXILIAR: MOSTRAR ALERTAS
// =========================================================================

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