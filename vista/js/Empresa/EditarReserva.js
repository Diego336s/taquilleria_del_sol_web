document.addEventListener("DOMContentLoaded", async function () {

    const urlParams = new URLSearchParams(window.location.search);
    const eventoId = urlParams.get("id");

    if (!eventoId) {
        console.error("No se recibió el ID del evento.");
        return;
    }

    Swal.fire({
        title: "Cargando los datos",
        text: "Por favor espera",
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    await cargarCategorias();
    await cargarEvento(eventoId);

    Swal.close();
});


/* ================================================
   🟧 CARGAR CATEGORÍAS EN EL SELECT
================================================ */
async function cargarCategorias() {
    const token = sessionStorage.getItem('userToken');
    if (!token) return;

    const select = document.getElementById("select_categoria");


    try {
        const respuesta = await fetch(ApiConexion + 'listarCategorias', {
            method: "GET",
            headers: {
                'Content-Type': 'application/json'

            }
        });

        const resultado = await respuesta.json();
        console.log("Resultaod", resultado)
        // Verifica si el API devuelve success y data
        if (resultado.success) {
            select.innerHTML = '<option value="">Seleccione una categoría</option>';

            resultado.data.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.id;   // ajusta según tu campo
                option.textContent = categoria.nombre; // ajusta según tu campo
                select.appendChild(option);
            });
        } else {
            console.error("Formato inesperado de la API:", resultado);
            select.innerHTML = '<option value="">No se pudieron cargar las categorías</option>';
        }
    } catch (error) {
        console.error("Error cargando categorías:", error);
        select.innerHTML = '<option value="">Error cargando categorías</option>';
    }
}


/* ================================================
   🟧 CARGAR LOS DATOS DEL EVENTO EN EL FORMULARIO
================================================ */
async function cargarEvento(id) {
    try {
        const res = await fetch(ApiConexion + "datos-eventos/" + id);
        const data = await res.json();

        if (!data.success) {
            console.error("No se pudo cargar el evento");
            return;
        }

        const ev = data.evento;

        // Llenar inputs
        document.getElementById("titulo").value = ev.titulo;
        document.getElementById("descripcion").value = ev.descripcion;
        document.getElementById("fecha").value = ev.fecha;
        document.getElementById("hora_inicio").value = ev.hora_inicio;
        document.getElementById("hora_fin").value = ev.hora_final;

        // Precios
        document.getElementById("precio_primer_piso").value = ev.precioPrimerPiso;
        document.getElementById("precio_segundo_piso").value = ev.precioSegundoPiso;
        document.getElementById("precio_general").value = ev.precioGeneral;

        // Categoría
        document.getElementById("select_categoria").value = ev.categoria_id;

    } catch (error) {
        console.error("Error cargando evento", error);
    }
}
