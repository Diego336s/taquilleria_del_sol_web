<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>

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
      max-width: 600px;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      color: white;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
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
    ⬅️ Volver a Inicio
  </button>

  <div class="dashboard-container">
    <h1>✏️ Editar Usuario</h1>

    <form id="formEditarUsuario">
      <input type="hidden" id="idUsuario">

      <label for="nombres">Nombres</label>
      <input type="text" id="nombres" required>

      <label for="apellidos">Apellidos</label>
      <input type="text" id="apellidos" required>

      <label for="documento">Documento</label>
      <input type="number" id="documento" required>

      <label for="telefono">Teléfono</label>
      <input type="text" id="telefono" required>

      <label for="correo">Correo</label>
      <input type="email" id="correo" required>

      <label for="fecha_nacimiento">Fecha de Nacimiento</label>
      <input type="date" id="fecha_nacimiento" required>

      <button type="submit" class="btn btn-save">💾 Guardar Cambios</button>
    </form>
  </div>

  <script>
    // 🔙 Función para volver sin dañar el diseño
    function volverDashboard() {
      window.location.href = 'Ver_Usuarios.php';
    }

    // 🧠 Obtener el ID del usuario desde la URL (ej: Editar_Usuario.php?id=1)
    const params = new URLSearchParams(window.location.search);
    const idUsuario = params.get('id');

    // 📡 Cargar datos del usuario
    async function cargarUsuario() {
      try {
        const response = await fetch(`http://localhost:8000/api/usuarios/${idUsuario}`);
        if (!response.ok) throw new Error("Error al cargar el usuario");

        const usuario = await response.json();

        document.getElementById("idUsuario").value = usuario.id;
        document.getElementById("nombres").value = usuario.nombre;
        document.getElementById("apellidos").value = usuario.apellido;
        document.getElementById("documento").value = usuario.documento;
        document.getElementById("telefono").value = usuario.telefono;
        document.getElementById("correo").value = usuario.correo;
        document.getElementById("fecha_nacimiento").value = usuario.fecha_nacimiento;
      } catch (error) {
        console.error(error);
        alert("Error al cargar los datos del usuario.");
      }
    }

    // 📝 Guardar cambios del usuario
    document.getElementById("formEditarUsuario").addEventListener("submit", async (e) => {
      e.preventDefault();

      const datos = {
        nombre: document.getElementById("nombres").value,
        apellido: document.getElementById("apellidos").value,
        documento: document.getElementById("documento").value,
        telefono: document.getElementById("telefono").value,
        correo: document.getElementById("correo").value,
        fecha_nacimiento: document.getElementById("fecha_nacimiento").value,
      };

      try {
        const response = await fetch(`http://localhost:8000/api/usuarios/${idUsuario}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(datos),
        });

        if (!response.ok) throw new Error("Error al actualizar el usuario");

        alert("✅ Usuario actualizado correctamente");
        volverDashboard();
      } catch (error) {
        console.error(error);
        alert("❌ Error al guardar los cambios");
      }
    });

    // Ejecutar la carga al abrir la página
    if (idUsuario) cargarUsuario();
  </script>

</body>
</html>
