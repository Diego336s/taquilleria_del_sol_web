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
                    <select name="select_categoria" id="select_categoria" class="form-select" required>

                    </select>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="form-group form-full">
                    <label>Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"></textarea>
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


<script>
    const ahora = new Date();

    // Convertir a hora de Colombia (UTC-5)
    const opciones = {
        timeZone: "America/Bogota",
        year: "numeric",
        month: "2-digit",
        day: "2-digit"
    };
    const formato = new Intl.DateTimeFormat("en-CA", opciones); // en-CA = formato YYYY-MM-DD
    const fechaColombia = formato.format(ahora); // Ej: 2025-11-24

    // Convertir esa fecha en objeto de Date
    const hoyColombia = new Date(fechaColombia);

    // Sumar un día
    hoyColombia.setDate(hoyColombia.getDate() + 1);

    // Convertir nuevamente a yyyy-mm-dd
    const minFecha = hoyColombia.toISOString().split("T")[0];

    // Colocar restricción
    document.getElementById("fecha").setAttribute("min", minFecha);
</script>

<script src="vista\js\Empresa\Registro_Evento.js"></script>