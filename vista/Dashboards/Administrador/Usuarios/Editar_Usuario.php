<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>✏️ Editar Usuario</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <style>
    body {
      background-image: url('../../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      color: #fff;
      margin: 0;
      padding: 0;
    }

    .container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 30px;
      width: 90%;
      max-width: 600px;
      margin: 60px auto;
      box-shadow: 0 10px 25px rgba(255, 107, 31, 0.6);
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    label {
      display: block;
      margin-top: 12px;
      font-weight: bold;
      color: #ffdca8;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      margin-top: 6px;
      background-color: rgba(255, 255, 255, 0.15);
      color: #fff;
      font-size: 1rem;
    }

    input:focus {
      outline: 2px solid #ff6b1f;
    }

    .btn {
      border: none;
      border-radius: 8px;
      padding: 10px 18px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 20px;
      width: 48%;
    }

    .btn-guardar {
      background-color: #28a745;
      color: white;
    }

    .btn-volver {
      background-color: #ff6b1f;
      color: white;
    }

    .acciones {
      display: flex;
      justify-content: space-between;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>✏️ Editar Usuario</h1>
    <form id="formEditar">
      <input type="hidden" id="idUsuario" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
      
      <div id="formBody">
        <p>Cargando datos del usuario...</p>
      </div>

      <div class="acciones">
        <button type="submit" class="btn btn-guardar" id="btnGuardar" style="display:none;">💾 Guardar Cambios</button>
        <button type="button" class="btn btn-volver" onclick="window.location.href='Usuarios_Registrados.php'">⬅️ Volver</button>
      </div>
    </form>
  </div>

  <script>
    const API_BASE = "http://127.0.0.1:8000/api/";
    const idUsuario = document.getElementById("idUsuario").value;
    const formBody = document.getElementById("formBody");
    const btnGuardar = document.getElementById("btnGuardar");

    // 🧩 Función para proteger HTML
    function escapeHtml(str) {
      return String(str || "").replace(/[&<>"']/g, function (m) {
        return ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" })[m];
      });
    }

    // 🧩 Normaliza la respuesta del backend
    function normalizeResponse(json) {
      if (!json) return [];
      if (Array.isArray(json)) return json;
      if (json.data && Array.isArray(json.data)) return json.data;
      if (json.usuarios && Array.isArray(json.usuarios)) return json.usuarios;
      return [];
    }

    // 🧩 Cargar usuario
    async function cargarUsuario() {
      if (!idUsuario) {
        formBody.innerHTML = "<p>ID inválido.</p>";
        return;
      }

      try {
        // Intentar primero endpoint individual
        let res = await fetch(`${API_BASE}listarClientes/${idUsuario}`);
        let data = null;

        if (res.ok) {
          data = await res.json().catch(() => null);
          if (data?.data) {
            mostrarFormulario(data.data);
            return;
          } else if (data?.id) {
            mostrarFormulario(data);
            return;
          }
        }

        // Fallback: listar todos y buscar por id
        res = await fetch(`${API_BASE}listarClientes`);
        if (!res.ok) throw new Error("Error al obtener usuarios");
        data = await res.json().catch(() => null);

        const usuarios = normalizeResponse(data);
        const usuario = usuarios.find(u => String(u.id) === String(idUsuario));

        if (!usuario) {
          formBody.innerHTML = "<p>No se encontraron datos del usuario.</p>";
          return;
        }

        mostrarFormulario(usuario);
      } catch (error) {
        console.error("Error:", error);
        formBody.innerHTML = "<p>Error al cargar los datos del usuario.</p>";
      }
    }

    // 🧩 Mostrar formulario con los datos
    function mostrarFormulario(u) {
      formBody.innerHTML = `
        <label>Nombre:</label>
        <input type="text" id="nombre" value="${escapeHtml(u.nombre)}" required>

        <label>Apellido:</label>
        <input type="text" id="apellido" value="${escapeHtml(u.apellido)}" required>

        <label>Documento:</label>
        <input type="text" id="documento" value="${escapeHtml(u.documento)}" required>

        <label>Teléfono:</label>
        <input type="text" id="telefono" value="${escapeHtml(u.telefono)}">

        <label>Correo:</label>
        <input type="email" id="correo" value="${escapeHtml(u.correo)}" required>
      `;
      btnGuardar.style.display = "inline-block";
    }

    // 🧩 Guardar cambios
    document.getElementById("formEditar").addEventListener("submit", async (e) => {
      e.preventDefault();

      const usuario = {
        nombre: document.getElementById("nombre").value,
        apellido: document.getElementById("apellido").value,
        documento: document.getElementById("documento").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value
      };

      try {
        const res = await fetch(`${API_BASE}actualizarUsuario/${idUsuario}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(usuario)
        });

        const json = await res.json().catch(() => null);

        if (res.ok && (json?.success || res.status === 200)) {
          alert("✅ Usuario actualizado correctamente.");
          window.location.href = "Usuarios_Registrados.php";
        } else {
          console.error("Respuesta de error:", json);
          alert("⚠️ No se pudo actualizar el usuario.");
        }
      } catch (error) {
        console.error(error);
        alert("❌ Error al conectar con el servidor.");
      }
    });

    // 🧩 Cargar datos al iniciar
    document.addEventListener("DOMContentLoaded", cargarUsuario);
  </script>

</body>
</html>
