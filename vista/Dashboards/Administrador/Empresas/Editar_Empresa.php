<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>✏️ Editar Empresa</title>

  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background-image: url('../../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      color: #fff;
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
      border: 1px solid rgba(255,255,255,0.2);
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
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.2);
      color: #fff;
      font-size: 15px;
    }

    .form-control:focus {
      border-color: #d9b76f;
      background: rgba(255,255,255,0.25);
      outline: none;
    }

    .btn {
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
      margin-top: 20px;
      font-size: 15px;
    }

    .btn-success {
      background-color: #3fa76d;
      color: white;
    }

    .btn-success:hover {
      background-color: #349461;
      transform: scale(1.05);
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #cda664;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 8px 20px rgba(205,166,100,0.4);
      font-weight: bold;
    }

    .btn-back:hover {
      background-color: #b89256;
      transform: scale(1.05);
    }

    .loading {
      text-align: center;
      color: #ddd;
    }
  </style>
</head>

<body>

  <!-- 🔙 Botón de volver -->
  <button class="btn-back" onclick="window.location.href='Ver_Empresas.php'">⬅ Volver</button>

  <div class="dashboard-container">
    <h1>✏️ Editar Empresa</h1>

    <form id="formEditar">
      <div id="contenidoFormulario" class="loading">Cargando datos...</div>

      <button type="submit" class="btn btn-success" id="btnGuardar" style="display:none;">
        💾 Guardar Cambios
      </button>
    </form>
  </div>

  <script src="../../../js/ApiConexion.js"></script>

  <script>
    const LIST_URL = ApiConexion + "listarEmpresas";
    const UPDATE_URL = ApiConexion + "actualizarEmpresa/";

    const params = new URLSearchParams(window.location.search);
    const idEmpresa = params.get("id");

    const form = document.getElementById("formEditar");
    const contenido = document.getElementById("contenidoFormulario");
    const btnGuardar = document.getElementById("btnGuardar");

    // Seguridad básica para evitar ruptura de HTML
    function escapeHtml(str) {
      return String(str || "")
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;");
    }

    // 🔹 Cargar información de la empresa
    document.addEventListener("DOMContentLoaded", async () => {
      if (!idEmpresa) {
        contenido.innerHTML = "<p>ID de empresa no válido.</p>";
        return;
      }

      try {
        const res = await fetch(LIST_URL);
        const data = await res.json();

        let lista = data.empresas || data.data || data;

        if (!Array.isArray(lista)) {
          lista = Object.values(lista).find(v => Array.isArray(v)) || [];
        }

        const empresa = lista.find(e => e.id == idEmpresa);

        if (!empresa) {
          contenido.innerHTML = "<p>No se encontró la empresa.</p>";
          return;
        }

        // Mostrar los datos en el formulario
        contenido.innerHTML = `
          <label>Nombre Empresa</label>
          <input class="form-control" name="nombre_empresa" value="${escapeHtml(empresa.nombre_empresa)}">

          <label>NIT</label>
          <input class="form-control" name="nit" value="${escapeHtml(empresa.nit)}">

          <label>Representante Legal</label>
          <input class="form-control" name="representante_legal" value="${escapeHtml(empresa.representante_legal)}">

          <label>Documento Representante</label>
          <input class="form-control" name="documento_representante" value="${escapeHtml(empresa.documento_representante)}">

          <label>Nombre Contacto</label>
          <input class="form-control" name="nombre_contacto" value="${escapeHtml(empresa.nombre_contacto)}">

          <label>Teléfono</label>
          <input class="form-control" name="telefono" value="${escapeHtml(empresa.telefono)}">

          <label>Correo Electrónico</label>
          <input type="email" class="form-control" name="correo" value="${escapeHtml(empresa.correo)}">
        `;

        btnGuardar.style.display = "inline-block";

      } catch (err) {
        contenido.innerHTML = "<p>Error al cargar los datos.</p>";
      }
    });

    // 💾 Guardar Cambios
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const datos = Object.fromEntries(new FormData(form).entries());

      try {
        const res = await fetch(UPDATE_URL + idEmpresa, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(datos)
        });

        const json = await res.json().catch(() => null);

        if (res.ok) {
          Swal.fire({
            icon: "success",
            title: "Empresa actualizada correctamente",
            showConfirmButton: false,
            timer: 1400
          }).then(() => {
            window.location.href = "Ver_Empresas.php";
          });

        } else {
          Swal.fire("Error", json?.message || "No se pudo actualizar", "error");
        }

      } catch (error) {
        Swal.fire("Error", "No se pudo conectar con el servidor", "error");
      }
    });
  </script>

</body>
</html>
