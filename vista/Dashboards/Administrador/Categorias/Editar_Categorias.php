<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Categoría</title>

    <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .dashboard-container {
            position: relative;
            z-index: 999;
            backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            margin: 60px auto;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1 {
            text-align: center;
            color: #fff;
        }

        label {
            color: #eee;
            margin-top: 15px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-success {
            background-color: #3fa76d;
            color: white;
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        a {
            color: #fff !important;
            font-weight: bold;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .loading {
            text-align: center;
            color: #ddd;
            font-size: large;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <h1>Editar Categoría</h1>

        <form id="formEditar">
            <div id="contenidoFormulario" class="loading">Cargando datos...</div>

            <button type="submit" class="btn-success" id="btnGuardar" style="display:none;">
                <i class="fas fa-save me-2"></i>Guardar Cambios
            </button>

            <p class="mt-4 text-center small">
                <a href="index.php?ruta=Ver_Categorias_Admin">← Volver</a>
            </p>
        </form>
    </div>

    <script src="vista/js/ApiConexion.js"></script>

    <script>
        // RUTAS AJUSTADAS A CATEGORÍAS
        const LIST_URL = ApiConexion + "listarCategorias";
        const UPDATE_URL = ApiConexion + "actualizarCategoria/";

        const params = new URLSearchParams(window.location.search);
        const idCategoria = params.get("id");

        const form = document.getElementById("formEditar");
        const contenido = document.getElementById("contenidoFormulario");
        const btnGuardar = document.getElementById("btnGuardar");

        function escapeHtml(str) {
            return String(str || "")
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#39;");
        }

        // Cargar información de la categoría
        document.addEventListener("DOMContentLoaded", async () => {

            if (!idCategoria) {
                contenido.innerHTML = "<p>ID de categoría no válido.</p>";
                return;
            }

            try {
                const res = await fetch(LIST_URL);
                const data = await res.json();
                const lista = data.data || data.categorias || data;

                const categoria = lista.find(c => c.id == idCategoria);

                if (!categoria) {
                    contenido.innerHTML = "<p>No se encontró la categoría.</p>";
                    return;
                }

                // Solo hay 1 campo: nombre
                contenido.innerHTML = `
          <label>Nombre de la Categoría</label>
          <input class="form-control" name="nombre" value="${escapeHtml(categoria.nombre)}">
        `;

                btnGuardar.style.display = "block";

            } catch (err) {
                contenido.innerHTML = "<p>Error al cargar los datos.</p>";
            }
        });

        // Guardar cambios
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const datos = Object.fromEntries(new FormData(form).entries());

            try {

                Swal.fire({
                    title: 'Actualizando Categoría...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                const res = await fetch(UPDATE_URL + idCategoria, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(datos)
                });

                const json = await res.json().catch(() => null);

                if (res.ok) {
                    Swal.fire({
                        icon: "success",
                        title: "Categoría actualizada",
                        timer: 1400,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = "index.php?ruta=Ver_Categorias_Admin";
                    });

                } else {
                    Swal.fire("Error", json?.message || "No se pudo actualizar", "error");
                }

            } catch (error) {
                Swal.fire("Error", "Error de conexión con el servidor", "error");
            }
        });
    </script>

</body>

</html>