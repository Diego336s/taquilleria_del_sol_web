<form id="formulario-registro" novalidate>

    <h2 class="h4 mb-4 text-center">Registro de Usuario</h2>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_Nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="id_Nombre" name="reg_Nombre" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_Apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="id_Apellido" name="reg_Apellido" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="id_Documento" class="form-label">Documento</label>
                <input type="number" class="form-control" id="id_Documento" name="reg_Documento" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_Sexo" class="form-label">Genero</label>
                <select class="form-select" id="id_Sexo" name="regSexo" required>
                    <option value="">Seleccionar</option>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_fecha_Nacimiento" class="form-label">Fecha de nacimiento</label>
                <input type="date" class="form-control" id="id_fecha_Nacimiento" name="reg_fecha_Nacimiento" required>
            </div>
        </div>

        <script>
            const hoy = new Date().toISOString().split("T")[0];
            document.getElementById("id_fecha_Nacimiento").setAttribute("max", hoy);
        </script>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="id_Telefono" class="form-label">Teléfono</label>
                <input type="number" class="form-control" id="id_Telefono" name="reg_Telefono" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="id_Email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="id_Email" name="reg_Email" required>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="id_Clave" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="id_Clave" name="reg_Clave" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleBtn_Clave">
                        <i class="fas fa-eye" id="toggleIcon_Clave"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="mb-3">
                <label for="id_ClaveConfirm" class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="id_ClaveConfirm" name="reg_ClaveConfirm" required>
                    <button class="btn btn-outline-secondary" type="button" id="toggleBtn_ClaveConfirm">
                        <i class="fas fa-eye" id="toggleIcon_ClaveConfirm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-check mb-3 mt-3">
        <input class="form-check-input" type="checkbox" value="" id="check_terminos">
        <label class="form-check-label text-white-50 small" for="check_terminos">
            He leído y acepto los <a href="index.php?ruta=terminos_condiciones">Términos y Condiciones</a>.
        </label>
    </div>

    <button type="submit" class="btn btn-success w-100 py-2 mt-4">Registrarme</button>

    <p class="mt-3 text-center small">
        ¿Ya tienes cuenta? <a href="login">Inicia sesión aquí</a>
    </p>

</form>