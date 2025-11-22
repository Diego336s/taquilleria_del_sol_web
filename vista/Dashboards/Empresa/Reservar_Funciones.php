<link rel="stylesheet" href="vista/css/ReservaFunciones.css?v=1.1">
<div class="form-container">
    <h1>Reservar / Crear Función</h1>
    <p class="form-subtitle">Completa los datos para crear la función.</p>

    <form action="" method="POST" enctype="multipart/form-data">

        <div class="form-grid">
            <!-- Título -->
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="titulo" required>
            </div>

            <!-- Categoría -->
            <div class="form-group">
                <label>Categoría</label>
                <select name="categoria" required>
                    <option value="">Selecciona categoría</option>
                </select>
            </div>

            <!-- Descripción -->
            <div class="form-group form-full">
                <label>Descripción</label>
                <textarea name="descripcion" rows="3"></textarea>
            </div>

            <!-- Empresa -->
            <div class="form-group">
                <label>Empresa</label>
                <input type="text" name="empresa">
            </div>

            <!-- Fecha -->
            <div class="form-group">
                <label>Fecha</label>
                <input type="date" name="fecha" required>
            </div>

            <!-- Hora inicio -->
            <div class="form-group">
                <label>Hora inicio (HH:MM:SS)</label>
                <input type="time" name="hora_inicio" required>
            </div>

            <!-- Hora fin -->
            <div class="form-group">
                <label>Hora final (HH:MM:SS)</label>
                <input type="time" name="hora_fin" required>
            </div>

            <!-- Precio Primer Piso -->
            <div class="form-group">
                <label>Precio Primer Piso</label>
                <input type="number" name="precio_primer_piso" min="0" required>
            </div>

            <!-- Precio Segundo Piso -->
            <div class="form-group">
                <label>Precio Segundo Piso</label>
                <input type="number" name="precio_segundo_piso" min="0" required>
            </div>

            <!-- Precio General -->
            <div class="form-group">
                <label>Precio General</label>
                <input type="number" name="precio_general" min="0" required>
            </div>

            <!-- Imagen -->
            <div class="form-group form-full">
                <label>Imagen</label>
                <input type="file" name="imagen" accept="image/*">
                <small>Formato permitido: JPG, PNG. Tamaño según servidor.</small>
            </div>

            <!-- Estado -->
            <div class="form-group">
                <label>Estado</label>
                <select name="estado" required>
                    <option value="">Selecciona estado</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- Botón Guardar -->
        <button type="submit" class="btn-guardar">
            <i class="bi bi-save"></i> Guardar Función
        </button>

        <!-- Volver -->
        <a href="dashboard" class="btn-volver">
            <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>

    </form>
</div>


<script src="./vista/js/Empresa/Dashboard_Empresa.js"></script>
