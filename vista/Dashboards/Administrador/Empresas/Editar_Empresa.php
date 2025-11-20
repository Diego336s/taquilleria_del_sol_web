<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>✏️ Editar Empresa</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
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

    .form-control:focus {
      border-color: #cda664;
      outline: none;
      background: rgba(255, 255, 255, 0.25);
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
      background-color: #3aa76d;
      color: #fff;
      box-shadow: 0 4px 15px rgba(58, 167, 109, 0.3);
    }

    .btn-success:hover {
      background-color: #329764;
      transform: scale(1.05);
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #cda664;
      color: white;
      box-shadow: 0 10px 20px rgba(205, 166, 100, 0.4);
    }

    .btn-back:hover {
      transform: scale(1.05);
      background-color: #b89358;
    }

    .loading {
      text-align: center;
      color: #ccc;
    }
  </style>
</head>
<body>

  <!-- 🔙 Botón de volver -->
  <button class="btn btn-back" onclick="window.location.href='Ver_Empresas.php'">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>✏️ Editar Empresa</h1>

    <form id="formEditar">
      <div id="contenidoFormulario" class="loading">Cargando datos...</div>
      <button type="submit" class="btn btn-success" style="display:none;" id="btnGuardar">💾 Guardar Cambios</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const API_URL = ApiConexion + "/api";``
    const params = new URLSearchParams(window.location.search);
    const idEmpresa = params.get("id");

    const form = document.getElementById("formEditar");
    const contenido = document.getElementById("contenidoFormulario");
    const btnGuardar = document.getElementById("btnGuardar");

    // 🔹 Cargar datos de la empresa
    document.addEventListener("DOMContentLoaded", async () => {
      if (!idEmpresa) {
        contenido.innerHTML = "<p>No se proporcionó un ID de empresa válido.</p>";
        return;
      }

      try {
        const res = await fetch(`${API_URL}listarEmpresas`);
        const data = await res.json();

        if (!res.ok || !data.data) {
          contenido.innerHTML = "<p>No se encontraron empresas registradas.</p>";
          return;
        }

        const empresa = data.data.find(e => e.id == idEmpresa);
        if (!empresa) {
          contenido.innerHTML = "<p>No se encontraron datos de la empresa.</p>";
          return;
        }

        // ✅ Mostrar datos
        contenido.innerHTML = `
          <label>Nombre Empresa</label>
          <input type="text" name="nombre_empresa" value="${empresa.nombre_empresa}" class="form-control" required>

          <label>NIT</label>
          <input type="text" name="nit" value="${empresa.nit}" class="form-control" required>

          <label>Representante Legal</label>
          <input type="text" name="representante_legal" value="${empresa.representante_legal}" class="form-control" required>

          <label>Documento Representante</label>
          <input type="text" name="documento_representante" value="${empresa.documento_representante}" class="form-control" required>

          <label>Nombre Contacto</label>
          <input type="text" name="nombre_contacto" value="${empresa.nombre_contacto || ""}" class="form-control">

          <label>Teléfono</label>
          <input type="text" name="telefono" value="${empresa.telefono || ""}" class="form-control">

          <label>Correo Electrónico</label>
          <input type="email" name="correo" value="${empresa.correo}" class="form-control" required>
        `;

        btnGuardar.style.display = "inline-block";

      } catch (error) {
        console.error(error);
        contenido.innerHTML = "<p>Error al cargar los datos de la empresa.</p>";
      }
    });

    // 💾 Guardar cambios
    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(form);
      const datos = Object.fromEntries(formData.entries());

      try {
        const res = await fetch(`${API_URL}actualizarEmpresa/${idEmpresa}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(datos),
        });

        const data = await res.json();

        // 🟢 Si la respuesta fue correcta, redirigir al detalle de empresa
        if (res.ok) {
          Swal.fire({
            icon: "success",
            title: "✅ Empresa actualizada correctamente",
            timer: 1500,
            showConfirmButton: false
          }).then(() => {
            // 🔁 Redirigir al detalle de la empresa
            window.location.href = `Ver_Empresas.php?id=${idEmpresa}`;
          });
        } else {
          Swal.fire("⚠️ Error", data.message || "No se pudo actualizar la empresa", "error");
        }

      } catch (error) {
        Swal.fire("❌ Error", "No se pudo conectar con el servidor", "error");
      }
    });
  </script>
</body>
</html>
