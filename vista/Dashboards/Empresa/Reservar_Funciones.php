<link rel="stylesheet" href="vista/css/Empresa/ReservaFunciones.css?v=1.1">

<div class="form-wrapper">
    <div class="form-card">

        <h1>Reservar / Crear Función</h1>
        <p class="form-subtitle">Completa los datos para crear la función.</p>

        <form action="" method="POST" enctype="multipart/form-data" id="form_registrar_evento">

            <div class="form-grid">

                <!-- TÍTULO -->
                <div class="form-group">
                    <label>Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                </div>

                <!-- CATEGORÍA (SELECT CARGADO DESDE LA API) -->
                <div class="form-group">
                    <label>Categoría</label>
                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                        <option value="">Cargando categorías...</option>
                    </select>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="form-group form-full">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
                </div>

                <!-- EMPRESA -->
                <div class="form-group">
                    <label>Empresa</label>
                    <input type="text" name="empresa" id="empresa" class="form-control">
                </div>

                <!-- FECHA -->
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" required>
                </div>

                <!-- HORAS -->
                <div class="form-group">
                    <label>Hora inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Hora final</label>
                    <input type="time" name="hora_fin" id="hora_fin" class="form-control" required>
                </div>

                <!-- PRECIOS -->
                <div class="form-group">
                    <label>Precio Primer Piso</label>
                    <input type="number"
                           name="precio_primer_piso"
                           id="precio_primer_piso"
                           class="form-control"
                           min="0"
                           required>
                </div>

                <div class="form-group">
                    <label>Precio Segundo Piso</label>
                    <input type="number"
                           name="precio_segundo_piso"
                           id="precio_segundo_piso"
                           class="form-control"
                           min="0"
                           required>
                </div>

                <div class="form-group">
                    <label>Precio General</label>
                    <input type="number"
                           name="precio_general"
                           id="precio_general"
                           class="form-control"
                           min="0"
                           required>
                </div>

                <!-- IMAGEN -->
                <div class="form-group form-full">
                    <label>Imagen</label>
                    <input type="file"
                           name="imagen"
                           id="imagen"
                           class="form-control"
                           accept="image/*">
                    <small>Formatos permitidos: JPG, PNG.</small>
                </div>

                <!-- ESTADO -->
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" id="estado" class="form-select" required>
                        <option value="">Selecciona estado</option>
                        <option value="activo">Activo</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>

            </div>

            <!-- BOTÓN GUARDAR -->
            <button type="submit" id="btn_registrar_evento" class="btn-success w-100 mt-3">
                Guardar Función
            </button>

            <!-- VOLVER -->
            <a href="dashboard" class="btn-volver">
                <i class="bi bi-arrow-left"></i> Volver al Dashboard
            </a>

        </form>
    </div>
</div>

<!-- Script para cargar categorías desde la API -->
<script>
async function cargarCategorias() {
    const token = sessionStorage.getItem('userToken');
    const select = document.getElementById("categoria_id");

    if (!token) {
        console.warn("No hay token disponible.");
        select.innerHTML = '<option value="">No autorizado</option>';
        return;
    }

    select.innerHTML = '<option value="">Cargando categorías...</option>';

    try {
        const respuesta = await fetch('https://tu-dominio.com/api/categorias', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            },
            mode: 'cors', // explícito para CORS
            credentials: 'include' // si necesitas cookies
        });

        // Revisar HTTP status
        if (!respuesta.ok) {
            console.error("Error HTTP:", respuesta.status, respuesta.statusText);
            select.innerHTML = `<option value="">Error cargando categorías (${respuesta.status})</option>`;
            return;
        }

        const texto = await respuesta.text();

        let resultado;
        try {
            resultado = JSON.parse(texto);
        } catch (e) {
            console.error("Respuesta no es JSON válido, probablemente HTML:", texto);
            select.innerHTML = '<option value="">Error cargando categorías (respuesta inválida)</option>';
            return;
        }

        if (resultado.success && Array.isArray(resultado.data)) {
            select.innerHTML = '<option value="">Seleccione una categoría</option>';

            resultado.data.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria.id;           // Ajusta según tu API
                option.textContent = categoria.nombre; // Ajusta según tu API
                select.appendChild(option);
            });
        } else {
            console.error("Formato inesperado de la API:", resultado);
            select.innerHTML = '<option value="">No se pudieron cargar las categorías</option>';
        }

    } catch (error) {
        console.error("Error cargando categorías (fetch):", error);
        select.innerHTML = '<option value="">Error cargando categorías</option>';
    }
}

document.addEventListener("DOMContentLoaded", cargarCategorias);
</script>

<script src="./vista/js/Empresa/Dashboard_Empresa.js"></script>
