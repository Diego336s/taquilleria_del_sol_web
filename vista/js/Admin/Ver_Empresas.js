document.addEventListener('DOMContentLoaded', () => {
    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del administrador en la UI
    populateAdminData();

    // Cargar lista de empresas
    ctrListarEmpresas();
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
// 🏢 FUNCIÓN: LISTAR EMPRESAS
// =========================================================================

async function ctrListarEmpresas() {
    const token = sessionStorage.getItem('userToken');
    const tbody = document.getElementById('tbody-empresas');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="7" class="loading">Cargando empresas...</td></tr>';

    const urlAPI = `${ApiConexion}empresas`;

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

            const empresas = data.empresas || data.data || data;
            if (!empresas || empresas.length === 0) {
                tbody.innerHTML = "<tr><td colspan='7' class='loading'>No hay empresas registradas</td></tr>";
                return;
            }

            empresas.forEach(empresa => {
                const estadoIcono = empresa.estado === 'Activa' || empresa.estado === 'activa' ? '🟢' : '🔴';
                const estadoTexto = empresa.estado || 'Inactiva';
                const row = `
                    <tr id="empresa-${empresa.id}">
                        <td>${empresa.id ?? '—'}</td>
                        <td>${empresa.nombre_empresa ?? empresa.nombre ?? '—'}</td>
                        <td>${empresa.nit ?? '—'}</td>
                        <td>${empresa.correo ?? '—'}</td>
                        <td>${empresa.telefono ?? '—'}</td>
                        <td>${estadoIcono} ${estadoTexto}</td>
                        <td>
                            <button class="btn btn-edit" onclick="ctrEditarEmpresa(${empresa.id})">
                                ✏️ Editar
                            </button>
                            <button class="btn btn-delete" onclick="ctrEliminarEmpresa(${empresa.id})">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML("beforeend", row);
            });

        } else {
            console.warn("Error en la respuesta del backend:", data.message);
            tbody.innerHTML = "<tr><td colspan='7' class='loading'>No se pudieron cargar las empresas</td></tr>";
        }
    } catch (error) {
        console.warn("Error de conexión con el backend:", error);
        tbody.innerHTML = "<tr><td colspan='7' class='loading'>No se pudieron cargar las empresas</td></tr>";
    }
}

// =========================================================================
// 📊 FUNCIÓN: RENDERIZAR DATOS SIMULADOS DE EMPRESAS
// =========================================================================

function renderTablaEmpresasSimuladas() {
    const tbody = document.getElementById('tbody-empresas');
    tbody.innerHTML = "";

    const empresasSimuladas = [
        { id: 1, nombre_empresa: "Teatro del Sol", nit: "901123456", correo: "teatro@sol.com", telefono: "3101234567", estado: "Activa" },
        { id: 2, nombre_empresa: "Eventos XYZ", nit: "902654321", correo: "contacto@eventosxyz.com", telefono: "3209876543", estado: "Inactiva" },
        { id: 3, nombre_empresa: "Corporación ABC", nit: "903789456", correo: "info@corpabc.com", telefono: "3004567890", estado: "Activa" }
    ];

    empresasSimuladas.forEach(empresa => {
        const estadoIcono = empresa.estado === 'Activa' ? '🟢' : '🔴';
        const row = `
            <tr id="empresa-${empresa.id}">
                <td>${empresa.id}</td>
                <td>${empresa.nombre_empresa}</td>
                <td>${empresa.nit}</td>
                <td>${empresa.correo}</td>
                <td>${empresa.telefono}</td>
                <td>${estadoIcono} ${empresa.estado}</td>
                <td>
                    <button class="btn btn-edit" onclick="ctrEditarEmpresa(${empresa.id})">
                        ✏️ Editar
                    </button>
                    <button class="btn btn-delete" onclick="ctrEliminarEmpresa(${empresa.id})">
                        🗑️ Eliminar
                    </button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML("beforeend", row);
    });
}

// =========================================================================
// ✏️ FUNCIÓN: EDITAR EMPRESA
// =========================================================================

function ctrEditarEmpresa(id) {
    // Redirigir a la página de edición
    window.location.href = `Editar_Empresa.php?id=${id}`;
}

// =========================================================================
// ➕ FUNCIÓN: CREAR EMPRESA
// =========================================================================

function crearEmpresa() {
    // Redirigir a la página de creación
    window.location.href = 'Crear_Empresa.php';
}

// =========================================================================
// 🗑️ FUNCIÓN: ELIMINAR EMPRESA
// =========================================================================

async function ctrEliminarEmpresa(id) {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        mostrarAlerta('error', 'Sesión inválida', 'No se encontró token de sesión.');
        return;
    }

    // Confirmación con Swal
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer. ¿Deseas eliminar esta empresa?",
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
        title: 'Eliminando empresa...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const urlAPI = `${ApiConexion}eliminarEmpresa/${id}`;

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
            const row = document.getElementById(`empresa-${id}`);
            if (row) {
                row.remove();
            }

            Swal.fire({
                icon: 'success',
                title: '¡Empresa Eliminada!',
                text: data.message || 'La empresa ha sido eliminada correctamente.',
                timer: 2000,
                showConfirmButton: false
            });

            // Recargar lista después de 2 segundos
            setTimeout(() => {
                ctrListarEmpresas();
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al eliminar', data.message || 'No se pudo eliminar la empresa.');
        }
    } catch (error) {
        Swal.close();
        console.error("Error al eliminar empresa:", error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor.');
    }
}

// =========================================================================
// ➕ FUNCIÓN: CREAR EMPRESA
// =========================================================================

function ctrCrearEmpresa() {
    // Redirigir a la página de creación
    window.location.href = 'Crear_Empresa.php';
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