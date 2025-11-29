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

    const form = document.getElementById("form_registrar_evento");
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        await ctrEditarEvento(eventoId);
    });
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

function mostrarAlerta(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonColor: '#3085d6'
    });
}

async function ctrEditarEvento(eventoId) {

    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'Por favor inicia sesión nuevamente.');
        return;
    }

    const titulo = document.getElementById("titulo").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const fecha = document.getElementById("fecha").value;

    const hora_inicio = document.getElementById("hora_inicio").value;
    const hora_final = document.getElementById("hora_fin").value;

    const precioPrimerPiso = document.getElementById("precio_primer_piso").value;
    const precioSegundoPiso = document.getElementById("precio_segundo_piso").value;
    const precioGeneral = document.getElementById("precio_general").value;

    const imagen = document.getElementById("imagen").files[0];
    const categoria_id = document.getElementById("select_categoria").value;
    const estado = "pendiente";


    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;


    if (!titulo || !descripcion || !fecha || !hora_inicio || !hora_final ||
        !precioPrimerPiso || !precioSegundoPiso || !precioGeneral ||
        !estado || !categoria_id || !imagen) {

        mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos.');
        return;
    }


    const inicio = new Date(`1970-01-01T${hora_inicio}`);
    const fin = new Date(`1970-01-01T${hora_final}`);

    if (inicio >= fin) {
       mostrarAlerta('error', 'Error hora', "La hora final debe ser mayor a la de inicio");
        return;
    }



    Swal.fire({
        title: 'Editando evento...',
        text: 'Por favor espera un momento.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    const formData = new FormData();

    formData.append("titulo", titulo);
    formData.append("descripcion", descripcion);
    formData.append("fecha", fecha);
    formData.append("hora_inicio", hora_inicio);
    formData.append("hora_final", hora_final);
    formData.append("estado", estado);
    formData.append("empresa_id", empresa_id);
    formData.append("categoria_id", categoria_id);

    if (imagen) {
        formData.append("imagen", imagen);
    }

    formData.append("precioPrimerPiso", precioPrimerPiso);
    formData.append("precioSegundoPiso", precioSegundoPiso);
    formData.append("precioGeneral", precioGeneral);

    // 🔥 Clave para que funcione correctamente con Laravel
    formData.append("_method", "PUT");

    try {
        const response = await fetch(`${ApiConexion}actualizarEventos/${eventoId}`, {
            method: "POST",       // 🔥 IMPORTANTE
            headers: {
                "Authorization": "Bearer " + token
            },
            body: formData
        });

        const data = await response.json();
        Swal.close();

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Evento actualizado!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

            setTimeout(() => {
                window.location.href = "index.php?ruta=HistorialDeEventos";
            }, 2000);

        } else {
            mostrarAlerta('error', 'Error al actualizar evento', data.message);
            console.log(data.error);
        }

    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de conexión', 'No se pudo comunicar con el servidor.');
    }
}
