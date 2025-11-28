
// Inicializar la tabla con el ID correcto
document.addEventListener("DOMContentLoaded", async function () {

    await ctrListarEmpresas();


    // 🔍 Filtro en tiempo real
    const buscador = document.getElementById('buscador');
    buscador.addEventListener('keyup', () => {
        const filtro = buscador.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaEventos tr');

        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
    });

});

function verReporte(eventoId) {
    window.location.href = ApiConexion + `reporte-evento/${eventoId}`;
}


// ======================================================
// 🏢 LISTAR EVENTOS REALIZADOS
// ======================================================
async function ctrListarEmpresas() {
    const userDataString = sessionStorage.getItem('userData');
    const token = sessionStorage.getItem('userToken');

    if (!userDataString || !token) {
        console.error("No se encontró la información del usuario o el token.");
        return
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    if (!empresa_id) {
        console.error("No se encontró el ID de la empresa.");
        return
    }

    const tbody = document.getElementById("tablaEventos");
    tbody.innerHTML = '<tr><td colspan="6" class="loading">Cargando eventos...</td></tr>';




    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'Por favor inicia sesión nuevamente.');
        return;
    }


    try {
        const respuesta = await fetch(`${ApiConexion}eventos-realizados-empresa/${empresa_id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();




        if (!data.success) {
            tbody.innerHTML = `<tr><td colspan='6' class='loading'>${data.message}</td></tr>`;
            return;
        }
        const eventos = data.eventos;
        tbody.innerHTML = "";
        eventos.forEach(ev => {

            const row = `
            <tr>
                <td>${ev.titulo}</td>
                <td>${ev.fecha}</td>
                <td>${ev.asientos_vendidos}</td>
                <td>$${Number(ev.total_dinero).toLocaleString("es-CO")}</td>
                <td>${ev.porcentaje_ocupacion}%</td>
                <td>
                    <button class="btn-reporte" onclick="verReporte(${ev.id})">
                     <img src="https://cdn-icons-png.flaticon.com/512/337/337946.png" alt="icon">
                     Reporte
                    </button>
                </td>
            </tr>
        `;

            tbody.insertAdjacentHTML('beforeend', row);
        });


    } catch (error) {
        console.error("Error de conexión:", error);
        tbody.innerHTML = "<tr><td colspan='6' class='loading'>No se pudo conectar al servidor.</td></tr>";
    }
}
