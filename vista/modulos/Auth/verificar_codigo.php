<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 450px; width: 100%;">
        <div class="card-body">
            <form id="form_verificar_codigo">

                <h1 class="h4 mb-3 text-center">Verificación de Código</h1>
                <p class="text-center text-white-50 small mb-4">
                    Ingresa el código que recibiste en tu correo electrónico.
                </p>

                <div class="mb-3">
                    <label for="id_codigo_verificacion" class="form-label">Código de Verificación</label>
                    <input type="number" class="form-control" id="id_codigo_verificacion" name="codigo_verificacion" oninput="if(this.value.length > 6) this.value = this.value.slice(0,6);"  required>
                </div>


                <button type="submit" class="btn btn-success w-100 py-2">Verificar</button>
                <p class="mt-3 text-center small">
                    <a href="index.php?ruta=login">Volver a Iniciar Sesión</a>
                </p>
            </form>
        </div>
    </div>
</div>