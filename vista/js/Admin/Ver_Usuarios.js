document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del administrador en la UI
    populateAdminData();

    // Cargar lista de usuarios
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
                        <td>${user.correo ?? '—'}</td>
                        <td>${user.telefono ?? '—'}</td>
                        <td>${user.rol ?? 'Cliente'}</td>
                        <td>${estadoIcono} ${estadoTexto}</td>
                        <td>
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
// 📊 FUNCIÓN: RENDERIZAR DATOS SIMULADOS DE USUARIOS
// =========================================================================

function renderTablaUsuariosSimulados() {
    const tbody = document.getElementById('tbody-usuarios');
    tbody.innerHTML = "";

    const usuariosSimulados = [
        { id: 1, nombre: "Juan", apellido: "Pérez", correo: "juanp@gmail.com", telefono: "3201234567", rol: "Cliente", estado: "Activo" },
        { id: 2, nombre: "Ana", apellido: "García", correo: "ana@test.com", telefono: "3109876543", rol: "Empresa", estado: "Inactivo" },
        { id: 3, nombre: "Carlos", apellido: "López", correo: "carlos@test.com", telefono: "3004567890", rol: "Cliente", estado: "Activo" }
    ];

    usuariosSimulados.forEach(user => {
        const estadoIcono = user.estado === 'Activo' ? '🟢' : '🔴';
        const row = `
            <tr id="usuario-${user.id}">
                <td>${user.id}</td>
                <td>${user.nombre}</td>
                <td>${user.apellido}</td>
                <td>${user.correo}</td>
                <td>${user.telefono}</td>
                <td>${user.rol}</td>
                <td>${estadoIcono} ${user.estado}</td>
                <td>
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
}

// =========================================================================
// 🔄 FUNCIÓN: CAMBIAR ESTADO DEL USUARIO
// =========================================================================

async function ctrCambiarEstadoUsuario(id, estadoActual) {
    const token = sessionStorage.getItem('userToken');
    const nuevoEstado = estadoActual === 'Activo' || estadoActual === 'activo' ? 'Inactivo' : 'Activo';

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    const result = await Swal.fire({
        title: '¿Cambiar estado?',
        text: `¿Deseas cambiar el estado del usuario a ${nuevoEstado}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) {
        return;
    }

    const urlAPI = `${ApiConexion}usuarios/${id}/estado`;

    try {
        const respuesta = await fetch(urlAPI, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({ estado: nuevoEstado })
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success === true) {
            mostrarAlerta('success', 'Estado actualizado', `El estado del usuario ha sido cambiado a ${nuevoEstado}.`);
            ctrListarUsuarios(); // Recargar la tabla
        } else {
            mostrarAlerta('error', 'Error al cambiar estado', data.message || 'No se pudo cambiar el estado del usuario.');
        }
    } catch (error) {
        console.error("Error al cambiar estado del usuario:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
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