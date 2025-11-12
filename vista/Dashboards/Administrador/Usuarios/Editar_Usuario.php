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
    }

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      margin: 60px auto;
      width: 90%;
      max-width: 700px;
      box-shadow: 0 10px 25px rgba(255, 255, 255, 0.15);
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
      letter-spacing: 0.5px;
    }

    label {
      color: #ddd;
      display: block;
      font-weight: 500;
      margin-top: 10px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      font-size: 15px;
      margin-top: 4px;
    }

    .btn {
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: all 0.3s ease;
      font-size: 15px;
      margin-top: 20px;
    }

    .btn-success {
      background-color: #4CAF50;
      color: #fff;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }

    .btn-success:hover {
      background-color: #43a047;
      transform: scale(1.05);
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #ff6b1f;
      color: white;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.4);
    }

    .btn-back:hover {
      transform: scale(1.05);
      background-color: #e65b10;
    }

    .loading {
      text-align: center;
      color: #ccc;
    }
  </style>
</head>
<body>

  <button class="btn btn-back" onclick="window.location.href='Ver_Clientes.php'">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>✏️ Editar Cliente</h1>

    <form id="formEditar">
      <div id="contenidoFormulario" class="loading">Cargando datos...</div>
      <button type="submit" class="btn btn-success" style="display:none;" id="btnGuardar">💾 Guardar Cambios</button>
    </form>
  </div>

  <script>
  (function(){
    const API_BASE = "http://127.0.0.1:8000/api/";
    const token = sessionStorage.getItem('userToken') || null;
    const params = new URLSearchParams(window.location.search);
    const idCliente = params.get("id");

    const form = document.getElementById("formEditar");
    const contenido = document.getElementById("contenidoFormulario");
    const btnGuardar = document.getElementById("btnGuardar");

    function buildHeaders() {
      const headers = { "Accept": "application/json" };
      if (token) headers["Authorization"] = "Bearer " + token;
      return headers;
    }

    function escapeHtml(str) {
      return String(str || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
    }

    async function cargarCliente() {
      if (!idCliente) {
        contenido.innerHTML = "<p>No se proporcionó ID del cliente.</p>";
        return;
      }

      let cliente = null;

      // 🟢 Intento 1: verCliente/{id}
      try {
        const res = await fetch(`${API_BASE}verCliente/${idCliente}`, { headers: buildHeaders() });
        if (res.ok) {
          const json = await res.json();
          cliente = json.data || json || null;
        }
      } catch (err) {
        console.warn("Fallo verCliente:", err);
      }

      // 🟢 Intento 2: listarClientes y buscar manualmente
      if (!cliente) {
        try {
          const res = await fetch(`${API_BASE}listarClientes`, { headers: buildHeaders() });
          if (res.ok) {
            const json = await res.json();
            const lista = json.data || json || [];
            cliente = Array.isArray(lista)
              ? lista.find(c => c.id == idCliente || c.idCliente == idCliente)
              : null;
          }
        } catch (err) {
          console.warn("Fallo listarClientes:", err);
        }
      }

      if (!cliente) {
        contenido.innerHTML = "<p>No se encontró el cliente. (Verifica la API o el ID)</p>";
        return;
      }

      // Llenar formulario
      contenido.innerHTML = `
        <label>Nombre</label>
        <input type="text" name="nombre" value="${escapeHtml(cliente.nombre || cliente.nombres || '')}" class="form-control" required>

        <label>Apellido</label>
        <input type="text" name="apellido" value="${escapeHtml(cliente.apellido || cliente.apellidos || '')}" class="form-control" required>

        <label>Documento</label>
        <input type="text" name="documento" value="${escapeHtml(cliente.documento || cliente.doc || '')}" class="form-control" required>

        <label>Teléfono</label>
        <input type="text" name="telefono" value="${escapeHtml(cliente.telefono || cliente.celular || '')}" class="form-control">

        <label>fecha nacimiento</label>
        <input type="text" name="fecha_nacimiento" value="${escapeHtml(cliente.fecha_nacimiento || cliente.fecha_nacimiento || '')}" class="form-control">

        <label>sexo</label>
        <input type="text" name="sexo" value="${escapeHtml(cliente.sexo || cliente.sexo || '')}" class="form-control">

        <label>Correo Electrónico</label>
        <input type="email" name="correo" value="${escapeHtml(cliente.correo || cliente.email || '')}" class="form-control" required>

        
        
      `;

      btnGuardar.style.display = "inline-block";
    }

    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(form).entries());

      try {
        const res = await fetch(`${API_BASE}actualizarCliente/${idCliente}`, {
          method: "PUT",
          headers: { 
            "Content-Type": "application/json",
            ...buildHeaders()
          },
          body: JSON.stringify(data)
        });

        const json = await res.json().catch(()=>null);
        if (res.ok) {
          Swal.fire("✅ Actualizado", json?.message || "Cliente actualizado correctamente", "success")
            .then(() => window.location.href = "Ver_Clientes.php");
        } else {
          Swal.fire("⚠️ Error", json?.message || "No se pudo actualizar el cliente.", "error");
        }
      } catch (err) {
        Swal.fire("❌ Error", "Error al conectar con el servidor", "error");
      }
    });

    document.addEventListener("DOMContentLoaded", cargarCliente);
  })();
  </script>
</body>
</html>
