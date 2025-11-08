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
      color: #fff;
      transition: background 0.5s, color 0.5s;
    }

    .dashboard-container {
      backdrop-filter: blur(12px);
      background-color: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      padding: 30px;
      margin: 40px auto;
      width: 90%;
      max-width: 950px;
      box-shadow: 0 10px 25px rgba(255, 107, 31, 0.6);
      animation: fadeIn 1s ease;
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
      font-size: 2rem;
    }

    .config-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: rgba(255, 255, 255, 0.12);
      border-radius: 15px;
      padding: 20px;
      box-shadow: 0 8px 20px rgba(255, 107, 31, 0.4);
      transition: 0.3s;
      text-align: center;
    }

    .card:hover {
      transform: scale(1.05);
      background: rgba(255, 255, 255, 0.2);
    }

    .card h3 {
      margin-bottom: 10px;
      font-size: 1.2rem;
    }

    .card input, .card select {
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

    .btn-back {
      position: fixed;
      top: 25px;
      left: 25px;
      background-color: #ff6b1f;
      color: white;
      z-index: 999;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
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
  </style>
</head>
<body>

  <button class="btn btn-back" onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Dashboard_Admin.php'">⬅️ Volver</button>

  <div class="dashboard-container">
    <h1>⚙️ Panel de Configuración Avanzada</h1>

    <div class="config-grid">

      <!-- Tema -->
      <div class="card">
        <h3>🎨 Tema de la Interfaz</h3>
        <select id="tema">
          <option value="claro">🌞 Claro</option>
          <option value="oscuro">🌙 Oscuro</option>
          <option value="naranja">🔥 Naranja</option>
        </select>
      </div>

      <!-- Idioma -->
      <div class="card">
        <h3>🌍 Idioma</h3>
        <select id="idioma">
          <option value="es">Español</option>
          <option value="en">English</option>
          <option value="fr">Français</option>
        </select>
      </div>

      <!-- Notificaciones -->
      <div class="card">
        <h3>🔔 Notificaciones</h3>
        <label class="switch">
          <input type="checkbox" id="notificaciones">
          <span class="slider"></span>
        </label>
      </div>

      <!-- Sonido -->
      <div class="card">
        <h3>🔊 Sonido del Sistema</h3>
        <label class="switch">
          <input type="checkbox" id="sonido">
          <span class="slider"></span>
        </label>
      </div>

      <!-- Fondo dinámico -->
      <div class="card">
        <h3>🖼 Fondo de Pantalla</h3>
        <select id="fondo">
          <option value="fondo1">✨ Brillante</option>
          <option value="fondo2">🌌 Nocturno</option>
          <option value="fondo3">🌄 Amanecer</option>
        </select>
      </div>

      <!-- Modo Mantenimiento -->
      <div class="card">
        <h3>🛠 Modo Mantenimiento</h3>
        <label class="switch">
          <input type="checkbox" id="mantenimiento">
          <span class="slider"></span>
        </label>
      </div>

    </div>

    <div style="text-align:center; margin-top:30px;">
      <button class="btn btn-primary" onclick="guardarConfiguracion()">💾 Guardar Configuración</button>
    </div>
  </div>

  <script>
    // Cargar configuración previa (localStorage)
    document.addEventListener("DOMContentLoaded", () => {
      const config = JSON.parse(localStorage.getItem("configSistema")) || {};
      document.getElementById("tema").value = config.tema || "claro";
      document.getElementById("idioma").value = config.idioma || "es";
      document.getElementById("notificaciones").checked = config.notificaciones || false;
      document.getElementById("sonido").checked = config.sonido || false;
      document.getElementById("fondo").value = config.fondo || "fondo1";
      document.getElementById("mantenimiento").checked = config.mantenimiento || false;
      aplicarTema(config.tema);
    });

    // Guardar configuración
    function guardarConfiguracion() {
      const config = {
        tema: document.getElementById("tema").value,
        idioma: document.getElementById("idioma").value,
        notificaciones: document.getElementById("notificaciones").checked,
        sonido: document.getElementById("sonido").checked,
        fondo: document.getElementById("fondo").value,
        mantenimiento: document.getElementById("mantenimiento").checked,
      };

      localStorage.setItem("configSistema", JSON.stringify(config));
      aplicarTema(config.tema);

      alert("✅ Configuración guardada exitosamente");
    }

    // Aplicar tema visual
    function aplicarTema(tema) {
      switch (tema) {
        case "oscuro":
          document.body.style.backgroundColor = "#1a1a1a";
          document.body.style.color = "#fff";
          break;
        case "naranja":
          document.body.style.backgroundColor = "#ff6b1f";
          document.body.style.color = "#fff";
          break;
        default:
          document.body.style.backgroundColor = "";
          document.body.style.color = "#fff";
      }
    }
  </script>

</body>
</html>
