<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>

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
    }

    .dashboard-container {
      backdrop-filter: blur(12px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      margin: 60px auto;
      width: 90%;
      max-width: 700px;
      box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #fff;
      font-weight: 600;
    }

    label {
      display: block;
      margin-top: 12px;
      color: #eee;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      margin-top: 4px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 15px;
    }

    .btn-success {
      background-color: #3fa76d;
      color: white;
      width: 100%;
      border: none;
      padding: 10px 18px;
      margin-top: 20px;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
      font-weight: bold;
      font-size: 15px;
    }

    .btn-success:hover {
      background-color: #349461;
      transform: scale(1.05);
    }

    .small a {
      color: #fff;
    }

    .loading {
      text-align: center;
      color: #ddd;
    }
  </style>
</head>

<body>

  <div class="dashboard-container">
    <h1>Editar Usuario</h1>

    <form id="formEditar">
      <div id="contenidoFormulario" class="loading">Cargando datos...</div>

      <button type="submit" class="btn-success" id="btnGuardar" style="display:none;">
        <i class="fas fa-save me-2"></i> Guardar Cambios
      </button>

      <p class="mt-4 text-center small">
        <a href="index.php?ruta=Ver_Usuarios_Admin">
          <i class="fas fa-arrow-left"></i> Volver
        </a>
      </p>
    </form>
  </div>

  <script src="vista/js/ApiConexion.js"></script>

  <script>
    const LIST_URL = ApiConexion + "listarClientes";
    const UPDATE_URL = ApiConexion + "actualizarCliente/";

    const params = new URLSearchParams(window.location.search);
    const idUsuario = params.get("id");

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

    document.addEventListener("DOMContentLoaded", async () => {
      if (!idUsuario) {
        contenido.innerHTML = "<p>ID de usuario no válido.</p>";
        return;
      }

      try {
        const res = await fetch(LIST_URL);
        const data = await res.json();

        let lista = data.clientes || data.data || data;
        if (!Array.isArray(lista)) {
          lista = Object.values(lista).find(v => Array.isArray(v)) || [];
        }

        const usuario = lista.find(u => u.id == idUsuario);

        if (!usuario) {
          contenido.innerHTML = "<p>No se encontró el usuario.</p>";
          return;
        }

        contenido.innerHTML = `
          <label>Nombre</label>
          <input class="form-control" name="nombre" value="${escapeHtml(usuario.nombre)}">

          <label>Apellido</label>
          <input class="form-control" name="apellido" value="${escapeHtml(usuario.apellido)}">

          <label>Documento</label>
          <input class="form-control" name="documento" value="${escapeHtml(usuario.documento)}">

          <label>Teléfono</label>
          <input class="form-control" name="telefono" value="${escapeHtml(usuario.telefono)}">

          <label>Fecha Nacimiento</label>
          <input 
            type="date" 
            class="form-control" 
            name="fecha_nacimiento" 
            value="${escapeHtml(usuario.fecha_nacimiento)}"
            max="${new Date().toISOString().split('T')[0]}"
          >

          <label>Correo</label>
          <input type="email" class="form-control" name="correo" value="${escapeHtml(usuario.correo)}">

          <label>Sexo</label>
          <select class="form-control" name="sexo">
            <option value="M" ${usuario.sexo === "M" ? "selected" : ""}>Masculino</option>
            <option value="F" ${usuario.sexo === "F" ? "selected" : ""}>Femenino</option>
          </select>

        `;

        btnGuardar.style.display = "inline-block";

      } catch (err) {
        contenido.innerHTML = "<p>Error al cargar los datos.</p>";
      }
    });

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const datos = Object.fromEntries(new FormData(form).entries());

      try {
        Swal.fire({
          title: 'Editando Usuarios...',
          text: 'Espera un momento.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        const res = await fetch(UPDATE_URL + idUsuario, {
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
            title: "Usuario actualizado correctamente",
            timer: 1400,
            showConfirmButton: false
          }).then(() => {
            window.location.href = "index.php?ruta=Ver_Usuarios_Admin";
          });

        } else {
          Swal.fire("Error", json?.message || "No se pudo actualizar", "error");
        }

      } catch (err) {
        Swal.fire("Error", "No se pudo conectar con el servidor", "error");
      }
    });
  </script>

</body>

</html>