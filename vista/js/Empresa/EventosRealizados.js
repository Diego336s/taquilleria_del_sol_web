
// Inicializar la tabla con el ID correcto
document.addEventListener("DOMContentLoaded",  async function () {

    await ctrListarEmpresas();


    // 🔍 Filtro en tiempo real
    const buscador = document.getElementById('buscador');
    buscador.addEventListener('keyup', () => {
        const filtro = buscador.value.toLowerCase();
        const filas = document.querySelectorAll('#tbody-empresas tr');

        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(filtro) ? '' : 'none';
        });
    });
});

// ======================================================
// 🏢 LISTAR EVENTOS REALIZADOS
// ======================================================
async function ctrListarEmpresas() {
    const token = sessionStorage.getItem('userToken');
    const tbody = document.getElementById('tablaEventos');

    if (!token) {
        mostrarAlerta('Sesión inválida. No se encontró token.');
        return;
    }

    tbody.innerHTML = '<tr><td colspan="6" class="loading">Cargando eventos...</td></tr>';

    try {
        const respuesta = await fetch(`${ApiConexion}eventos-realizados-empresa/`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            }
        });

        const data = await respuesta.json();


        const eventos = data.eventos;

        if (eventos.length === 0) {
            tbody.innerHTML = "<tr><td colspan='6' class='loading'>No hay eventos finalizados.</td></tr>";
            return;
        }

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
                        Ver Reporte
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
