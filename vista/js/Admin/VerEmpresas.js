document.addEventListener('DOMContentLoaded', () => {
    validarSesionAdmin();
    ctrListarEmpresas();
});

// ======================================================
// 🧾 VALIDAR SESIÓN DEL ADMINISTRADOR
// ======================================================
function validarSesionAdmin() {
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('Sesión inválida. Inicia sesión nuevamente.');
        window.location.replace("../../../index.php?ruta=login");
        return;
    }

    try {
        const userData = JSON.parse(userDataString);
        const rol = (userData.rol || '').toLowerCase();

        if (!['admin', 'administrador', 'administrator'].includes(rol)) {
            console.warn('⚠️ El usuario no tiene rol de administrador.');
        }
    } catch (e) {
        console.error('Error al procesar los datos del usuario:', e);
        sessionStorage.clear();
        window.location.replace("../../../index.php?ruta=login");
    }
}

// ======================================================
// 🏢 LISTAR EMPRESAS DESDE EL BACKEND
// ======================================================
async function ctrListarEmpresas() {
    const token = sessionStorage.getItem('userToken');
    const tbody = document.getElementById('tbody-empresas');

    if (!token) {
        mostrarAlerta('Sesión inválida. No se encontró token.');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="10" class="loading">Cargando empresas...</td></tr>';

    try {
        const respuesta = await fetch(`${ApiConexion}listarEmpresas`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success && Array.isArray(data.data)) {
            const empresas = data.data;

            if (empresas.length === 0) {
                tbody.innerHTML = "<tr><td colspan='10' class='loading'>No hay empresas registradas.</td></tr>";
                return;
            }

            tbody.innerHTML = "";
            empresas.forEach(emp => {
                const row = `
                    <tr id="empresa-${emp.id}">
                        <td>${emp.nombre_empresa}</td>
                        <td>${emp.nit}</td>
                        <td>${emp.representante_legal}</td>
                        <td>${emp.documento_representante}</td>
                        <td>${emp.nombre_contacto || ''}</td>
                        <td>${emp.telefono || ''}</td>
                        <td>${emp.correo}</td>
                        <td>
                            <button class="btn-action btn-edit" onclick="ctrEditarEmpresa(${emp.id})"><i class="fa-solid fa-pen-to-square"></i> Editar</button>
                            
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tbody.innerHTML = "<tr><td colspan='10' class='loading'>Error al cargar empresas.</td></tr>";
        }
    } catch (error) {
        console.error("Error de conexión:", error);
        tbody.innerHTML = "<tr><td colspan='10' class='loading'>No se pudo conectar al servidor.</td></tr>";
    }
}

// ======================================================
// 👁️ VER EMPRESA ESPECÍFICA
// ======================================================
function ctrVerEmpresa(id) {
    window.location.href = `Ver_Empresa.php?id=${id}`;
}

// ======================================================
// ➕ REGISTRAR NUEVA EMPRESA
// ======================================================
async function ctrRegistrarEmpresa() {
    const token = sessionStorage.getItem('userToken');

    const empresa = {
        nombre_empresa: document.getElementById("nombre_empresa").value,
        nit: document.getElementById("nit").value,
        representante_legal: document.getElementById("representante_legal").value,
        documento_representante: document.getElementById("documento_representante").value,
        nombre_contacto: document.getElementById("nombre_contacto").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value,
        clave: document.getElementById("clave").value
    };

    try {
        const respuesta = await fetch(`${ApiConexion}registrarEmpresa`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            },
            body: JSON.stringify(empresa)
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success) {
            mostrarAlerta('Empresa registrada correctamente.');
            ctrListarEmpresas();
            document.getElementById("formEmpresa").reset();
        } else {
            mostrarAlerta(data.message || 'No se pudo registrar la empresa.');
        }
    } catch (error) {
        console.error(error);
        mostrarAlerta('Error de conexión. Inténtalo más tarde.');
    }
}

// ======================================================
// ✏️ EDITAR EMPRESA
// ======================================================
function ctrEditarEmpresa(id) {
    window.location.href = `index.php?ruta=Editar_Empresa&id=${id}`;
}

// ======================================================
// 🗑️ ELIMINAR EMPRESA
// ======================================================
async function ctrEliminarEmpresa(id) {
    const token = sessionStorage.getItem('userToken');

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

    try {
        const respuesta = await fetch(`${ApiConexion}eliminarEmpresa/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.message) {
            Swal.fire({
                icon: 'success',
                title: '¡Eliminada!',
                text: 'La empresa ha sido eliminada correctamente.',
                timer: 2000,
                showConfirmButton: false
            });
            ctrListarEmpresas();
        } else {
            mostrarAlerta('error', 'Error al eliminar', data.message || 'No se pudo eliminar la empresa.');
        }
    } catch (error) {
        console.error(error);
        mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor. Inténtalo más tarde.');
    }
}

// ======================================================
// ⚠️ ALERTAS (Solo alert)
// ======================================================
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
        // Fallback por si SweetAlert no está disponible
        alert(`${title} (${icon}): ${text}`);
    }
}
