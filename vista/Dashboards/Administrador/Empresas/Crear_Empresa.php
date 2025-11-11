<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Nueva Empresa</title>
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
    }

    .form-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      margin: 50px auto;
      width: 90%;
      max-width: 700px;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      color: #fff;
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
    }

    label {
      display: block;
      margin-top: 12px;
      font-weight: bold;
      color: #fff;
    }

    input {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 10px;
      margin-top: 5px;
      margin-bottom: 15px;
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 15px;
      outline: none;
    }

    input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .btn {
      display: inline-block;
      padding: 12px 20px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      transition: 0.3s;
    }

    .btn-save {
      background-color: #ff6b1f;
      color: #fff;
      box-shadow: 0 5px 15px rgba(255, 107, 31, 0.5);
      width: 100%;
    }

    .btn-save:hover {
      background-color: #ff853d;
      transform: scale(1.03);
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
      transform: scale(1.05);
    }

    .message {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
    }

    .password-toggle {
      position: relative;
    }

    .toggle-btn {
      position: absolute;
      right: 15px;
      top: 35px;
      background: none;
      border: none;
      color: #fff;
      cursor: pointer;
      font-size: 16px;
    }

  </style>
</head>
<body>
  <button class="btn btn-back" onclick="volverEmpresas()">⬅️ Volver</button>

  <div class="form-container">
    <h1>Registrar Nueva Empresa</h1>

    <form id="formEmpresa" onsubmit="return false;">
      <label>Nombre de la Empresa</label>
      <input type="text" id="nombre_empresa" placeholder="Ej: Teatro del Sol" required>

      <label>NIT</label>
      <input type="text" id="nit" placeholder="Ej: 900123456-7" required>

      <label>Representante Legal</label>
      <input type="text" id="representante_legal" placeholder="Ej: Carlos Pérez" required>

      <label>Documento Representante</label>
      <input type="text" id="documento_representante" placeholder="Ej: 12345678" required>

      <label>Nombre del Contacto</label>
      <input type="text" id="nombre_contacto" placeholder="Ej: Ana López" required>

      <label>Teléfono</label>
      <input type="text" id="telefono" placeholder="Ej: 3201234567" required>

      <label>Correo</label>
      <input type="email" id="correo" placeholder="Ej: contacto@empresa.com" required>

      <label>Contraseña</label>
      <div class="password-toggle">
        <input type="password" id="clave" placeholder="Crea una contraseña segura" required>
        <button type="button" class="toggle-btn" onclick="togglePassword()">👁️</button>
      </div>
      <button type="submit" class="btn btn-save" id="btnGuardar">💾 Guardar Empresa</button>
    </form>

    <p id="mensaje" class="message"></p>
  </div>

  <!-- Archivos JS -->
  <script src="../../../js/ApiConexion.js"></script>
  <script>
    function volverEmpresas() {
      window.location.href = "Ver_Empresas.php";
    }

    function togglePassword() {
      const passInput = document.getElementById("clave");
      passInput.type = passInput.type === "password" ? "text" : "password";
    }

    // ✅ Función principal corregida
    async function ctrRegistrarEmpresa() {
      const mensaje = document.getElementById("mensaje");
      mensaje.textContent = "Registrando empresa...";

      const data = {
        nombre_empresa: document.getElementById("nombre_empresa").value.trim(),
        nit: document.getElementById("nit").value.trim(),
        representante_legal: document.getElementById("representante_legal").value.trim(),
        documento_representante: document.getElementById("documento_representante").value.trim(),
        nombre_contacto: document.getElementById("nombre_contacto").value.trim(),
        telefono: document.getElementById("telefono").value.trim(),
        correo: document.getElementById("correo").value.trim(),
        clave: document.getElementById("clave").value.trim()
      };

      // Validación rápida
      for (const key in data) {
        if (!data[key]) {
          mensaje.textContent = "⚠️ Por favor completa todos los campos.";
          mensaje.style.color = "yellow";
          return;
        }
      }

      try {
        const response = await fetch("http://127.0.0.1:8000/api/registrarEmpresa", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok) {
          throw new Error(result.message || "Error al registrar la empresa");
        }

        mensaje.textContent = "✅ Empresa registrada con éxito.";
        mensaje.style.color = "#4caf50";

        // Redirige automáticamente
        setTimeout(() => {
          window.location.href = "Ver_Empresas.php";
        }, 1500);

      } catch (error) {
        console.error("Error:", error);
        mensaje.textContent = `❌ ${error.message}`;
        mensaje.style.color = "red";
      }
    }

    // Asigna el evento al botón
    document.getElementById("btnGuardar").addEventListener("click", ctrRegistrarEmpresa);
  </script>
</body>
</html>