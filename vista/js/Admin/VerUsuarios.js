document.addEventListener('DOMContentLoaded', () => {
    checkAuthAndRedirect();

    populateAdminData();

    ctrListarUsuarios();
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
        const adminRoles = ['admin', 'administrator', 'administrador'];
        if (!adminRoles.includes(userRole.toLowerCase())) {
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
// 👥 FUNCIÓN: LISTAR USUARIOS
// =========================================================================

async function ctrListarUsuarios() {
    const token = sessionStorage.getItem('userToken');
    const tbody = document.getElementById('tbody-usuarios');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="8" class="loading text-center">Cargando usuarios...</td></tr>';

    const urlAPI = `${ApiConexion}usuarios`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            tbody.innerHTML = "";

            const usuarios = data.usuarios || data.data || data;
            if (!usuarios || usuarios.length === 0) {
                tbody.innerHTML = "<tr><td colspan='8' class='loading text-center'>No hay usuarios registrados</td></tr>";
                return;
            }

            usuarios.forEach(user => {
                 const estadoIcono = user.estado === 'Activo' || user.estado === 'activo' ? '🟢' : '🔴';
                 const estadoTexto = user.estado || 'Inactivo';
                 const row = `
                     <tr id="usuario-${user.id}">
                         <td>${user.id ?? '—'}</td>
                         <td>${user.nombre ?? user.nombres ?? '—'}</td>
                         <td>${user.apellido ?? user.apellidos ?? '—'}</td>
                         <td>${user.documento ?? user.numero_documento ?? '—'}</td>
                         <td>${user.telefono ?? user.celular ?? '—'}</td>
                         <td>${user.correo ?? user.email ?? '—'}</td>
                         <td>${user.fecha_nacimiento ?? user.fechaNacimiento ?? '—'}</td>
                         <td>${user.rol ?? user.tipo ?? 'Cliente'}</td>
                         <td>${estadoIcono} ${estadoTexto}</td>
                         <td>
                             <button class="btn btn-edit" onclick="ctrEditarUsuario(${user.id})">
                                 ✏️ Editar
                             </button>
                             <button class="btn btn-edit" onclick="ctrCambiarEstadoUsuario(${user.id}, '${user.estado}')">
                                 🔄 Cambiar Estado
                             </button>
                             <button class="btn btn-delete" onclick="ctrEliminarUsuario(${user.id})">
                                 🗑️ Eliminar
                             </button>
                         </td>
                     </tr>
                 `;
                 tbody.insertAdjacentHTML("beforeend", row);
             });

        } else {
            console.warn("Error en la respuesta del backend:", data.message);
            tbody.innerHTML = "<tr><td colspan='8' class='loading text-center'>No se pudieron cargar los usuarios</td></tr>";
        }
    } catch (error) {
        console.warn("Error de conexión con el backend:", error);
        tbody.innerHTML = "<tr><td colspan='8' class='loading text-center'>No se pudieron cargar los usuarios</td></tr>";
    }
}



// =========================================================================
// 🗑️ FUNCIÓN: ELIMINAR USUARIO
// =========================================================================

async function ctrEliminarUsuario(id) {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    // Confirmación con Swal
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer. ¿Deseas eliminar este usuario?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) {
        return;
    }

    // Mostrar loading
    Swal.fire({
        title: 'Eliminando usuario...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const urlAPI = `${ApiConexion}eliminarCliente/${id}`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();
        Swal.close();

        if (respuesta.ok && data.success === true) {
            // Remover fila de la tabla
            const row = document.getElementById(`usuario-${id}`);
            if (row) {
                row.remove();
            }

            Swal.fire({
                icon: 'success',
                title: '¡Usuario Eliminado!',
                text: data.message || 'El usuario ha sido eliminado correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            // Recargar lista después de 2 segundos
            setTimeout(() => {
                ctrListarUsuarios();
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al eliminar', data.message || 'No se pudo eliminar el usuario.');
        }
    } catch (error) {
        Swal.close();
        console.error("Error al eliminar usuario:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
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