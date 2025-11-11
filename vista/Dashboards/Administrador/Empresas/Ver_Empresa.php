<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🏢 Ver Empresa</title>
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

    .info-section {
      margin-bottom: 20px;
    }

    .info-label {
      color: #ddd;
      font-weight: 500;
      margin-bottom: 5px;
    }

    .info-value {
      background: rgba(255, 255, 255, 0.15);
      padding: 10px;
      border-radius: 8px;
      color: #fff;
      font-size: 15px;
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

    .btn-edit {
      background-color: #3aa76d;
      color: #fff;
      box-shadow: 0 4px 15px rgba(58, 167, 109, 0.3);
    }

    .btn-edit:hover {
      background-color: #329764;
      transform: scale(1.05);
    }

    .loading {
      text-align: center;
      color: #ccc;
    }

    .error {
      text-align: center;
      color: #ff6b6b;
    }
  </style>
</head>
<body>

  <!-- 🔙 Botón de volver -->
  <button class="btn btn-back" onclick="window.location.href='Ver_Empresas.php'">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>🏢 Información de la Empresa</h1>

    <div id="contenidoEmpresa" class="loading">Cargando datos...</div>
  </div>

  <script src="../../../js/ApiConexion.js"></script>
  <script>
    const params = new URLSearchParams(window.location.search);
    const idEmpresa = params.get("id");

    const contenido = document.getElementById("contenidoEmpresa");

    // 🔹 Cargar datos de la empresa
    document.addEventListener("DOMContentLoaded", async () => {
      if (!idEmpresa) {
        contenido.innerHTML = "<p class='error'>No se proporcionó un ID de empresa válido.</p>";
        return;
      }

      try {
        const res = await fetch(`${ApiConexion}empresa/${idEmpresa}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + (sessionStorage.getItem('userToken') || '')
          }
        });

        const data = await res.json();

        if (res.ok && data.success && data.data) {
          const empresa = data.data;

          contenido.innerHTML = `
            <div class="info-section">
              <div class="info-label">ID</div>
              <div class="info-value">${empresa.id}</div>
            </div>

            <div class="info-section">
              <div class="info-label">Nombre Empresa</div>
              <div class="info-value">${empresa.nombre_empresa}</div>
            </div>

            <div class="info-section">
              <div class="info-label">NIT</div>
              <div class="info-value">${empresa.nit}</div>
            </div>

            <div class="info-section">
              <div class="info-label">Representante Legal</div>
              <div class="info-value">${empresa.representante_legal}</div>
            </div>

            <div class="info-section">
              <div class="info-label">Documento Representante</div>
              <div class="info-value">${empresa.documento_representante}</div>
            </div>

            <div class="info-section">
              <div class="info-label">Nombre Contacto</div>
              <div class="info-value">${empresa.nombre_contacto || 'No especificado'}</div>
            </div>

            <div class="info-section">
              <div class="info-label">Teléfono</div>
              <div class="info-value">${empresa.telefono || 'No especificado'}</div>
            </div>

            <div class="info-section">
              <div class="info-label">Correo Electrónico</div>
              <div class="info-value">${empresa.correo}</div>
            </div>

            <button class="btn btn-edit" onclick="editarEmpresa(${empresa.id})">✏️ Editar Empresa</button>
          `;
        } else {
          contenido.innerHTML = "<p class='error'>No se encontraron datos de la empresa.</p>";
        }
      } catch (error) {
        console.error(error);
        contenido.innerHTML = "<p class='error'>Error al cargar los datos de la empresa.</p>";
      }
    });

    // ✏️ Redirigir a editar
    function editarEmpresa(id) {
      window.location.href = `Editar_Empresa.php?id=${id}`;
    }
  </script>
</body>
</html>