<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Configuración del Administrador</title>
  <link rel="stylesheet" href="../../../css/admin.css?v=1.0">
  <style>
    /* === ESTILOS BASE === */
    body {
      background-image: url('../../css/img/fondo.png');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      color: #fff;
      transition: background 0.5s, color 0.5s;
    }

    .dashboard-container {
      backdrop-filter: blur(12px);
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 40px;
      margin: 60px auto;
      width: 90%;
      max-width: 1000px;
      box-shadow: 0 10px 30px rgba(255, 107, 31, 0.6);
      animation: fadeIn 1s ease;
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 40px;
      font-size: 2.2rem;
      letter-spacing: 1px;
    }

    /* === BOTONES === */
    .btn {
      padding: 12px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: 0.3s;
      margin: 15px 10px 0 0;
    }

    .btn-primary {
      background-color: #ff6b1f;
      color: white;
      box-shadow: 0 5px 15px rgba(255, 107, 31, 0.5);
    }

    .btn-primary:hover {
      transform: scale(1.05);
    }

    .btn-danger {
      background-color: #d63031;
      color: white;
      box-shadow: 0 5px 15px rgba(214, 48, 49, 0.5);
    }

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #ff6b1f;
      color: white;
      z-index: 999;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
    }

    /* === TARJETAS DE CONFIGURACIÓN === */
    .config-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
    }

    .card {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 18px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(255, 107, 31, 0.3);
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.25);
    }

    .card h3 {
      margin-bottom: 15px;
      font-size: 1.3rem;
    }

    .card input,
    .card select {
      width: 90%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      outline: none;
      font-size: 15px;
      text-align: center;
      background: rgba(255, 255, 255, 0.25);
      color: #fff;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 24px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.4s;
      border-radius: 24px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: 0.4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #ff6b1f;
    }

    input:checked + .slider:before {
      transform: translateX(26px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* === FORMULARIO CONTRASEÑA === */
    .password-section input {
      margin-bottom: 10px;
    }

    .footer-buttons {
      text-align: center;
      margin-top: 40px;
    }

    /* === ALERTAS PERSONALIZADAS === */
    .alerta {
      position: fixed;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(255, 107, 31, 0.95);
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(255, 107, 31, 0.6);
      animation: fadeIn 0.5s ease;
      display: none;
    }
  </style>
</head>

<body>

  <button class="btn btn-back" onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Dashboard_Admin.php'">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>⚙️ Configuración del Administrador</h1>

      <div class="card password-section">
        <h3>🔒 Cambiar Contraseña</h3>
        <input type="password" id="passActual" placeholder="Contraseña actual">
        <input type="password" id="passNueva" placeholder="Nueva contraseña">
        <input type="password" id="passConfirm" placeholder="Confirmar nueva contraseña">
        <button class="btn btn-primary" onclick="cambiarContrasena()">Actualizar</button>
      </div>

      <div class="card">
        <h3>🚪 Cerrar Sesión</h3>
        <p>Finaliza tu sesión de administrador.</p>
        <button class="btn btn-danger" onclick="cerrarSesion()">Cerrar Sesión</button>
      </div>
    </div>

  <div id="alerta" class="alerta"></div>

  <script>
   
    // === Cambiar contraseña ===
    function cambiarContrasena() {
      const actual = document.getElementById("passActual").value;
      const nueva = document.getElementById("passNueva").value;
      const confirm = document.getElementById("passConfirm").value;

      if (!actual || !nueva || !confirm) {
        mostrarAlerta("⚠️ Completa todos los campos");
        return;
      }

      if (nueva !== confirm) {
        mostrarAlerta("❌ Las contraseñas no coinciden");
        return;
      }

      if (nueva.length < 6) {
        mostrarAlerta("🔑 La nueva contraseña debe tener al menos 6 caracteres");
        return;
      }

      // Simulación de cambio (sin backend)
      mostrarAlerta("✅ Contraseña actualizada correctamente");
      document.getElementById("passActual").value = "";
      document.getElementById("passNueva").value = "";
      document.getElementById("passConfirm").value = "";
    }

    // === Cerrar sesión ===
    function cerrarSesion() {
      mostrarAlerta("🚪 Cerrando sesión...");
      setTimeout(() => {
        window.location.href = "/taquilleria_del_sol_web/vista/modulos/login.php";
      }, 1500);
    }

    // === Aplicar tema visual ===
    function aplicarTema(tema) {
      switch (tema) {
        case "oscuro":
          document.body.style.backgroundColor = "#1a1a1a";
          break;
        case "naranja":
          document.body.style.backgroundColor = "#ff6b1f";
          break;
        default:
          document.body.style.backgroundColor = "";
      }
    }

    // === Mostrar alerta temporal ===
    function mostrarAlerta(mensaje) {
      const alerta = document.getElementById("alerta");
      alerta.textContent = mensaje;
      alerta.style.display = "block";
      setTimeout(() => alerta.style.display = "none", 2500);
    }
  </script>

</body>
</html>
