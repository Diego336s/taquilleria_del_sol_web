document.addEventListener("DOMContentLoaded", async function () {

    const userDataString = sessionStorage.getItem('userData');
    const token = sessionStorage.getItem('userToken');

    if (!userDataString || !token) {
        console.error("No se encontró la información del usuario o el token.");
        return;
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    await cargarEventos(empresa_id);

    // Solo un listener para búsqueda
    document.getElementById("buscador").addEventListener("input", filtrarEventos);
    document.getElementById("filtroEstado").addEventListener("change", filtrarEventos);
});


async function cargarEventos(empresaId) {
    const tabla = document.getElementById("tablaEventos");
    tabla.innerHTML = `<tr><td colspan="9">Cargando eventos...</td></tr>`;

    try {
        const res = await fetch(ApiConexion + `historial-eventos/${empresaId}`);
        const data = await res.json();

        if (!data.success) {
            tabla.innerHTML = `<tr><td colspan="9">No hay eventos disponibles.</td></tr>`;
            return;
        }

        tabla.innerHTML = ""; // limpiar

        data.eventos.forEach(ev => {

            let imagen = ev.imagen
                ? `<img src="${ev.imagen}" class="img-mini">`
                : "Sin imagen";

            let categoria = ev.categoria?.nombre ?? "Sin categoría";
            const botones =
                ev.estado === "activo"
                    ? `
       <span class="no-acciones">No disponible</span>
        ` : `
                     <div class="acciones-botones">
            <button class="btn-editar" onclick="editarEvento(${ev.id})">
                <i class="fas fa-edit"></i> Editar
            </button>

            <button class="btn-eliminar" onclick="eliminarEvento(${ev.id}, ${ev.empresa_id})">
                <i class="fas fa-trash"></i> Eliminar
            </button>
        </div>
                    `;

            const row = `
                <tr>
                    <td>${ev.titulo}</td>
                    <td>${ev.descripcion ?? "Sin descripción"}</td>
                    <td>${ev.fecha}</td>
                    <td>${ev.hora_inicio}</td>
                    <td>${ev.hora_final}</td>
                    <td class="estado-${ev.estado}">${ev.estado}</td>
                    <td>${categoria}</td>
                    <td>${imagen}</td>
                    
                    <td>
                       ${botones}
                    </td>
                </tr>
            `;

            tabla.insertAdjacentHTML("beforeend", row);
        });

    } catch (error) {
        console.error("Error al cargar eventos", error);
        tabla.innerHTML = `<tr><td colspan="9">Error cargando eventos.</td></tr>`;
    }
}



async function eliminarEvento(id, empresa_id) {

    Swal.fire({
        title: "¿Eliminar evento?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar"
    }).then(async (result) => {

        if (result.isConfirmed) {

            Swal.fire({
                title: "Eliminando evento",
                text: "Por favor espera",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            try {
                const res = await fetch(ApiConexion + `eliminarEventos/${id}`, {
                    method: "DELETE",
                   
                });

                const data = await res.json();
                console.log(data);
                Swal.close();
                if (data.success) {
                    Swal.fire("Eliminado", data.message, "success");

                 cargarEventos(empresa_id);

                } else {
                    Swal.fire("Error", data.message ?? "No se pudo eliminar", "error");
                }

            } catch (error) {
                Swal.fire("Error", "No se pudo conectar con el servidor", "error");
            }
        }
    });
}




function filtrarEventos() {
    const estadoFiltro = document.getElementById("filtroEstado").value;
    const texto = document.getElementById("buscador").value.toLowerCase();

    const filas = document.querySelectorAll("#tablaEventos tr");

    filas.forEach(fila => {

        const titulo = fila.children[0]?.textContent.toLowerCase();
        const descripcion = fila.children[1]?.textContent.toLowerCase();
        const estado = fila.children[5]?.textContent.toLowerCase();
        const categoria = fila.children[6]?.textContent.toLowerCase();

        let visible = true;

        // Filtro texto
        if (
            texto &&
            !titulo.includes(texto) &&
            !descripcion.includes(texto) &&
            !categoria.includes(texto)
        ) {
            visible = false;
        }

        // Filtro por estado
        if (estadoFiltro !== "todos" && estado !== estadoFiltro) {
            visible = false;
        }

        fila.style.display = visible ? "" : "none";
    });
}


function editarEvento(id) {
    window.location.href = `index.php?ruta=EditarReservas&id=${id}`;
}
