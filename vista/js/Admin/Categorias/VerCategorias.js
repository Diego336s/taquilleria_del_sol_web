document.addEventListener('DOMContentLoaded', () => {
    ctrListarCategorias();
});

// ======================================================
// 🏷 LISTAR CATEGORÍAS DESDE EL BACKEND
// ======================================================
async function ctrListarCategorias() {

    const token = sessionStorage.getItem('userToken');
    const tbody = document.getElementById('tbody-categorias');

    if (!token) {
        mostrarAlerta('Sesión inválida. No se encontró token.');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="3" class="loading">Cargando categorías...</td></tr>';

    try {

        const respuesta = await fetch(`${ApiConexion}listarCategorias`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.success && Array.isArray(data.data)) {

            const categorias = data.data;

            if (categorias.length === 0) {
                tbody.innerHTML = "<tr><td colspan='3' class='loading'>No hay categorías registradas.</td></tr>";
                return;
            }

            tbody.innerHTML = "";

            categorias.forEach(cat => {
                const row = `
                    <tr id="categoria-${cat.id}">
                        <td>${cat.nombre}</td>

                        <td>
                            <button class="btn-action btn-edit" onclick="ctrEditarCategoria(${cat.id})">
                                <i class="fa-solid fa-pen-to-square"></i> Editar
                            </button>

                            <button class="btn-action btn-delete" onclick="ctrEliminarCategoria(${cat.id})">
                                <i class="fa-solid fa-trash"></i> Eliminar
                            </button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });

        } else {
            tbody.innerHTML = "<tr><td colspan='3' class='loading'>Error al cargar categorías.</td></tr>";
        }

    } catch (error) {
        console.error("Error de conexión:", error);
        tbody.innerHTML = "<tr><td colspan='3' class='loading'>No se pudo conectar al servidor.</td></tr>";
    }
}

// ======================================================
// ✏️ EDITAR CATEGORIA
// ======================================================
function ctrEditarCategoria(id) {
    window.location.href = `index.php?ruta=Editar_Categorias&id=${id}`;
}

// ======================================================
// 🗑️ ELIMINAR CATEGORÍA
// ======================================================
async function ctrEliminarCategoria(id) {
    const token = sessionStorage.getItem('userToken');

    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer. ¿Deseas eliminar esta categoría?",
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

    Swal.fire({
        title: 'Eliminando Categoría...',
        text: 'Este proceso puede tardar unos segundos. No cierres esta ventana.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    try {
        const respuesta = await fetch(`${ApiConexion}eliminarCategoria/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        const data = await respuesta.json();

        if (respuesta.ok && data.message) {
            Swal.fire({
                icon: 'success',
                title: '¡Eliminada!',
                text: 'La categoria ha sido eliminada correctamente.',
                timer: 2000,
                showConfirmButton: false
            });
            ctrListarCategorias();
        } else {
            mostrarAlerta('error', 'Error al eliminar', data.message || 'No se pudo eliminar la categoria.');
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
