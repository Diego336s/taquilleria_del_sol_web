<?php
// vista/Dashboards/Administrador/Empresas/Editar_Empresa.php
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Empresa</title>

  <!-- Mantiene el mismo diseño del dashboard -->
  <link rel="stylesheet" href="../../../css/Dashboards/Administrador.css?v=1.0">

  <style>
    body {
      background-image: url('../../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 30px;
      margin: 40px auto;
      width: 90%;
      max-width: 700px;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      color: white;
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
      color: #fff;
    }

    input {
      padding: 10px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      background: rgba(255, 255, 255, 0.2);
      color: white;
    }

    input:focus {
      outline: 2px solid #ff6b1f;
      background: rgba(255, 255, 255, 0.3);
    }

    .btn {
      display: inline-block;
      padding: 10px 18px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-save {
      background-color: #4CAF50;
      color: #fff;
      box-shadow: 0 10px 20px rgba(0, 255, 100, 0.5);
    }

    .btn-save:hover {
      transform: scale(1.05);
      background-color: #43a047;
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #9c4012e6;
      color: #fff;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      z-index: 999;
    }

    .btn-back:hover {
      background-color: #9c4012e6;
      transform: scale(1.05);
    }
  </style>
</head>
<body>

  <!-- Botón fijo arriba -->
  <button class="btn btn-back" onclick="volverDashboard()">
    Volver a Inicio
  </button>

  <div class="dashboard-container">
    <h1>🏢 Editar Empresa</h1>

    <form id="formEditarEmpresa">
      <input type="hidden" id="idEmpresa">

      <label for="nombre_empresa">Nombre de la Empresa</label>
      <input type="text" id="nombre_empresa" required>

      <label for="nit">NIT</label>
      <input type="text" id="nit" required>

      <label for="representante_legal">Representante Legal</label>
      <input type="text" id="representante_legal" required>

      <label for="documento_representante">Documento del Representante</label>
      <input type="text" id="documento_representante" required>

      <label for="nombre_contacto">Nombre del Contacto</label>
      <input type="text" id="nombre_contacto" required>

      <label for="telefono">Teléfono</label>
      <input type="text" id="telefono" required>

      <label for="correo">Correo Electrónico</label>
      <input type="email" id="correo" required>

      <label for="clave">Clave</label>
      <input type="password" id="clave">

      <button type="submit" class="btn btn-save">💾 Guardar Cambios</button>
    </form>
  </div>

  <script>
    // 🔙 Volver sin dañar el diseño
    function volverDashboard() {
      window.location.href = 'Ver_Empresas.php';
    }

    // Obtener ID de la empresa desde la URL (ej: Editar_Empresa.php?id=3)
    const params = new URLSearchParams(window.location.search);
    const idEmpresa = params.get('id');

    // Cargar los datos de la empresa
    async function cargarEmpresa() {
      try {
        const response = await fetch(`http://localhost:8000/api/empresas/${idEmpresa}`);
        if (!response.ok) throw new Error("Error al cargar los datos de la empresa");

        const empresa = await response.json();

        document.getElementById("idEmpresa").value = empresa.id;
        document.getElementById("nombre_empresa").value = empresa.nombre_empresa;
        document.getElementById("nit").value = empresa.nit;
        document.getElementById("representante_legal").value = empresa.representante_legal;
        document.getElementById("documento_representante").value = empresa.documento_representante;
        document.getElementById("nombre_contacto").value = empresa.nombre_contacto;
        document.getElementById("telefono").value = empresa.telefono;
        document.getElementById("correo").value = empresa.correo;
      } catch (error) {
        console.error(error);
        alert("❌ Error al cargar la empresa.");
      }
    }

    // 📝 Guardar cambios
    document.getElementById("formEditarEmpresa").addEventListener("submit", async (e) => {
      e.preventDefault();

      const datos = {
        nombre_empresa: document.getElementById("nombre_empresa").value,
        nit: document.getElementById("nit").value,
        representante_legal: document.getElementById("representante_legal").value,
        documento_representante: document.getElementById("documento_representante").value,
        nombre_contacto: document.getElementById("nombre_contacto").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value,
        clave: document.getElementById("clave").value,
      };

      try {
        const response = await fetch(`http://localhost:8000/api/empresas/${idEmpresa}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(datos),
        });

        if (!response.ok) throw new Error("Error al actualizar la empresa");

        alert("✅ Empresa actualizada correctamente");
        volverDashboard();
      } catch (error) {
        console.error(error);
        alert("❌ Error al guardar los cambios");
      }
    });

    // Ejecutar carga automática
    if (idEmpresa) cargarEmpresa();
  </script>

</body>
</html>
