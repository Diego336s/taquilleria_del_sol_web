
// =========================================================================
// FUNCIÓN: cargar categorías en el formulario de registro de eventos
// =========================================================================

document.addEventListener("DOMContentLoaded", () => {

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
    cargarCategorias();

    const form = document.getElementById("form_registrar_evento");
    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        await ctrRegistrarEvento();
    });
}, []);

function mostrarAlerta(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonColor: '#3085d6'
    });
}

// =========================================================================
// FUNCIÓN: Registrar Evento
// =========================================================================
async function ctrRegistrarEvento() {

    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        mostrarAlerta('error', 'Sesión inválida', 'Por favor inicia sesión nuevamente.');
        return;
    }

    // Obtener valores del formulario
    const titulo = document.getElementById("titulo").value.trim();
    const descripcion = document.getElementById("descripcion").value.trim();
    const fecha = document.getElementById("fecha").value;

    const hora_inicio_raw = document.getElementById("hora_inicio").value;
    const hora_final_raw = document.getElementById("hora_fin").value;

    const hora_inicio = hora_inicio_raw + ":00";
    const hora_final = hora_final_raw + ":00";

    const precioPrimerPiso = document.getElementById("precio_primer_piso").value;
    const precioSegundoPiso = document.getElementById("precio_segundo_piso").value;
    const precioGeneral = document.getElementById("precio_general").value;

    const imagen = document.getElementById("imagen").files[0];
    const estado = "pendiente";

    // Aquí obtienes el valor correcto del SELECT
    const categoriaSelect = document.getElementById("select_categoria");
    const categoria_id = categoriaSelect ? categoriaSelect.value : null;

    if (!titulo || !descripcion || !fecha || !hora_inicio || !hora_final ||
        !precioPrimerPiso || !precioSegundoPiso || !precioGeneral ||
        !estado || !categoria_id || !imagen) {

        mostrarAlerta('error', 'Campos incompletos', 'Por favor, rellena todos los campos.');
        return;
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;
   

    Swal.fire({
        title: 'Registrando evento...',
        text: 'Por favor, espera un momento.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    // FormData
    const formData = new FormData();

    formData.append("titulo", titulo.trim());
    formData.append("descripcion", descripcion.trim());
    formData.append("fecha", fecha); // formato: YYYY-MM-DD
    formData.append("hora_inicio", hora_inicio); // HH:MM
    formData.append("hora_final", hora_final);   // HH:MM
    formData.append("estado", estado);
    formData.append("categoria_id", categoria_id);
    formData.append("empresa_id", empresa_id);
    formData.append("imagen", imagen); // Debe ser un File
    formData.append("precioPrimerPiso", precioPrimerPiso);
    formData.append("precioSegundoPiso", precioSegundoPiso);
    formData.append("precioGeneral", precioGeneral);


    try {
        const response = await fetch(`${ApiConexion}registrarEventos`, {
            method: "POST",
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
                title: '¡Evento Registrado!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });

            document.getElementById("form_registrar_evento").reset();
           setTimeout(() => {
                window.location.href = "index.php?ruta=dashboard-empresa";
            }, 2000);
        } else {
            mostrarAlerta('error', 'Error al registrar evento', data.message);
            if(data.error){
            console.log("Error", data.error);
            }
        }

    } catch (error) {
        Swal.close();
        mostrarAlerta('error', 'Error de conexión', 'No se pudo comunicar con el servidor.');
        console.error(error);
    }
}
