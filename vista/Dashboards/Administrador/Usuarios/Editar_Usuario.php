<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>✏️ Editar Cliente</title>

  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background-image: url('../../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      color: #fff;
      margin:0;
      padding:0;
    }

    .dashboard-container {
      backdrop-filter: blur(12px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      margin: 60px auto;
      width: 90%;
      max-width: 700px;
      box-shadow: 0 10px 25px rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255,255,255,0.2);
    }

    h1 { text-align:center; font-weight:600; margin-bottom:30px; }
    label { display:block; margin-top:10px; color:#f0f0f0; }

    .form-control {
      width:100%; padding:10px; margin-top:5px;
      border-radius:8px;
      border:1px solid rgba(255,255,255,0.2);
      background:rgba(255,255,255,0.15);
      color:white;
    }

    .btn {
      padding:10px 18px;
      border:none;
      border-radius:8px;
      cursor:pointer;
      font-weight:bold;
      transition:.3s;
      font-size:15px;
      margin-top:20px;
    }

    .btn-success { background:#4CAF50; color:white; }
    .btn-success:hover { background:#43a047; transform:scale(1.05); }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #ff6b1f;
      color: white;
    }

    .btn-back:hover { transform: scale(1.05); background-color: #e65b10; }

    .loading { text-align:center; color:#ddd; }
  </style>
</head>

<body>

  <button class="btn btn-back" onclick="window.location.href='Ver_Usuarios.php'">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>✏️ Editar Cliente</h1>

    <form id="formEditar">
      <div id="contenidoFormulario" class="loading">Cargando datos...</div>
      <button type="submit" class="btn btn-success" id="btnGuardar" style="display:none;">💾 Guardar Cambios</button>
    </form>
  </div>

  <!-- API BASE -->
  <script src="../../../js/ApiConexion.js"></script>

  <script>
  (function () {

    const LIST_URL = ApiConexion + "listarClientes";
    const UPDATE_URL = ApiConexion + "actualizarCliente/"; 

    const params = new URLSearchParams(window.location.search);
    const idCliente = params.get("id");

    const form = document.getElementById("formEditar");
    const contenido = document.getElementById("contenidoFormulario");
    const btnGuardar = document.getElementById("btnGuardar");

    // ---- Convertir caracteres especiales ----
    function escapeHtml(str) {
      return String(str || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
    }

    // ---- Cargar datos del cliente ----
    async function cargarCliente() {
      try {
        const res = await fetch(LIST_URL);
        const data = await res.json();

        let lista = data.clientes || data.data || data;

        if (!Array.isArray(lista)) {
          lista = Object.values(lista).find(v => Array.isArray(v)) || [];
        }

        const cliente = lista.find(c => c.id == idCliente);

        if (!cliente) {
          contenido.innerHTML = "<p>No se encontró el cliente.</p>";
          return;
        }

        contenido.innerHTML = `
          <label>Nombre</label>
          <input name="nombre" class="form-control" value="${escapeHtml(cliente.nombre)}">

          <label>Apellido</label>
          <input name="apellido" class="form-control" value="${escapeHtml(cliente.apellido)}">

          <label>Documento</label>
          <input name="documento" class="form-control" value="${escapeHtml(cliente.documento)}">

          <label>Teléfono</label>
          <input name="telefono" class="form-control" value="${escapeHtml(cliente.telefono)}">

          <label>Fecha Nacimiento</label>
          <input type="date" name="fecha_nacimiento" class="form-control" value="${escapeHtml(cliente.fecha_nacimiento)}">

          <label>Correo</label>
          <input type="email" name="correo" class="form-control" value="${escapeHtml(cliente.correo)}">
        `;

        btnGuardar.style.display = "inline-block";

      } catch (err) {
        contenido.innerHTML = "❌ Error cargando datos.";
      }
    }

    // ---- Guardar Cambios ----
    form.addEventListener("submit", async e => {
      e.preventDefault();

      const data = Object.fromEntries(new FormData(form).entries());

      try {
        const res = await fetch(UPDATE_URL + idCliente, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data)
        });

        const json = await res.json().catch(() => null);

        if (res.ok) {
          Swal.fire("✔️ Éxito", "Cliente actualizado correctamente.", "success")
            .then(() => window.location.href = "Ver_Usuarios.php");
        } else {
          Swal.fire("❌ Error", json?.message || "Error al actualizar.");
        }

      } catch (err) {
        Swal.fire("❌ Error", "No se pudo conectar con el servidor.");
      }
    });

    document.addEventListener("DOMContentLoaded", cargarCliente);

  })();
  </script>

</body>
</html>
