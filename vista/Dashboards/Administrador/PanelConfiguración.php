<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración del Sistema</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <style>
    /* === ESTILO GLOBAL === */
    body {
      background-image: url('../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      color: #f5f5f5;
    }

    /* === CONTENEDOR PRINCIPAL === */
    .dashboard-container {
      backdrop-filter: blur(12px);
      background: rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 40px;
      margin: 60px auto;
      width: 90%;
      max-width: 800px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    h1 {
      text-align: center;
      font-weight: 600;
      font-size: 28px;
      color: #fff;
      margin-bottom: 35px;
      letter-spacing: 0.5px;
    }

    /* === FORMULARIO === */
    .form-group {
      margin-bottom: 22px;
    }

    label {
      display: block;
      color: #e0e0e0;
      font-weight: 500;
      margin-bottom: 6px;
      font-size: 15px;
    }

    .form-control {
      width: 100%;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.25);
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      font-size: 15px;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.25);
      outline: none;
      border-color: #cda664;
    }

    select.form-control {
      cursor: pointer;
    }

    /* === BOTONES === */
    .btn {
      display: inline-block;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      font-size: 15px;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background-color: #cda664;
      color: #fff;
      box-shadow: 0 4px 15px rgba(205, 166, 100, 0.3);
    }

    .btn-primary:hover {
      background-color: #b89358;
      transform: translateY(-2px);
    }

    .btn-success {
      background-color: #3aa76d;
      color: #fff;
      box-shadow: 0 4px 15px rgba(58, 167, 109, 0.3);
    }

    .btn-success:hover {
      background-color: #329764;
      transform: translateY(-2px);
    }

    /* === BOTÓN VOLVER === */
    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #cda664;
      color: #fff;
      z-index: 999;
      box-shadow: 0 6px 20px rgba(205, 166, 100, 0.4);
      padding: 10px 16px;
    }

    .btn-back:hover {
      background-color: #b89358;
      transform: scale(1.05);
    }

    /* === RESPONSIVO === */
    @media (max-width: 600px) {
      .dashboard-container {
        padding: 25px;
      }

      h1 {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>

  <!-- 🔙 Botón de volver -->
  <button class="btn btn-back" onclick="volverDashboard()">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>⚙️ Configuración del Sistema</h1>

    <form id="config-form">
      <div class="form-group">
        <label for="empresa">🏢 Empresa</label>
        <select id="empresa" class="form-control">
          <option value="">Seleccione una empresa</option>
          <option value="empresa1">Taquillería del Sol</option>
          <option value="empresa2">Eventos del Norte</option>
        </select>
      </div>

      <div class="form-group">
        <label for="funcion">🎭 Función</label>
        <select id="funcion" class="form-control">
          <option value="">Seleccione una función</option>
          <option value="funcion1">Obra de Teatro</option>
          <option value="funcion2">Concierto</option>
        </select>
      </div>

      <div class="form-group">
        <label for="precioBase">💰 Precio Base</label>
        <input type="number" id="precioBase" value="50000" class="form-control">
      </div>

      <div class="form-group">
        <label for="horaApertura">🕗 Hora de Apertura</label>
        <input type="time" id="horaApertura" value="08:00" class="form-control">
      </div>

      <div class="form-group">
        <label for="horaCierre">🕙 Hora de Cierre</label>
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
          <option value="mantenimiento">🟡 Mantenimiento</option>
          <option value="inactivo">🔴 Inactivo</option>
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

    // 💾 Guardar configuración (simulado)
    function guardarConfiguracion() {
      const configuracion = {
        empresa: document.getElementById('empresa').value,
        funcion: document.getElementById('funcion').value,
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
