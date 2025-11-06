<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración del Sistema</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <style>
    body {
      background-image: url('../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 30px;
      margin: 40px auto;
      width: 90%;
      max-width: 900px;
      box-shadow: 0 10px 25px rgba(255, 107, 31, 0.6);
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
    }

    label {
      color: #fff;
      font-weight: bold;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-control {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      outline: none;
      font-size: 16px;
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.3);
    }

    select.form-control {
      cursor: pointer;
    }

    .btn {
      padding: 10px 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
      margin-right: 10px;
    }

    .btn-primary {
      background-color: #9c4012e6;
      color: white;
      box-shadow: 0 5px 15px rgba(255, 107, 31, 0.5);
    }

    .btn-primary:hover {
      transform: scale(1.05);
    }

    .btn-success {
      background-color: #28a745;
      color: #fff;
      box-shadow: 0 5px 15px rgba(0, 255, 100, 0.4);
    }

    .btn-success:hover {
      transform: scale(1.05);
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #9c4012e6;
      color: white;
      z-index: 999;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
    }

    .btn-back:hover {
      transform: scale(1.05);
    }
  </style>
</head>
<body>

  <!-- 🔙 Botón de volver -->
  <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver al Dashboard</button>

  <div class="dashboard-container">
    <h1>⚙️ Configuración del Sistema</h1>

    <form id="config-form">
      <div class="form-group">
        <label for="precioBase">💰 Precio Base General</label>
        <input type="number" id="precioBase" value="50000" class="form-control">
      </div>

      <div class="form-group">
        <label for="horaApertura">🕗 Horario de Apertura</label>
        <input type="time" id="horaApertura" value="08:00" class="form-control">
      </div>

      <div class="form-group">
        <label for="horaCierre">🕙 Horario de Cierre</label>
        <input type="time" id="horaCierre" value="22:00" class="form-control">
      </div>

      <div class="form-group">
        <label for="correoNotificaciones">📧 Correo de Notificaciones</label>
        <input type="email" id="correoNotificaciones" value="admin@taquilleria.com" class="form-control">
      </div>

      <div class="form-group">
        <label for="estadoSistema">🔒 Estado del Sistema</label>
        <select id="estadoSistema" class="form-control">
          <option value="activo">🟢 Activo</option>
          <option value="mantenimiento">🟡 En Mantenimiento</option>
        </select>
      </div>

      <button type="button" class="btn btn-success" onclick="guardarConfiguracion()">💾 Guardar Cambios</button>
    </form>
  </div>

  <script>
    // 🔙 Volver al Dashboard
    function volverDashboard() {
      window.location.href = '/taquilleria_del_sol_web/index.php?ruta=dashboard-admin';
    }

    // 💾 Guardar configuración (solo simulado por ahora)
    function guardarConfiguracion() {
      const configuracion = {
        precioBase: document.getElementById('precioBase').value,
        horaApertura: document.getElementById('horaApertura').value,
        horaCierre: document.getElementById('horaCierre').value,
        correo: document.getElementById('correoNotificaciones').value,
        estado: document.getElementById('estadoSistema').value
      };

      console.log("Configuración guardada:", configuracion);
      alert("✅ Configuración actualizada correctamente (simulación)");
    }
  </script>

</body>
</html>
