<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Registrar Nueva Empresa</title>
  <link rel="stylesheet" href="vista/css/admin.css?v=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* === CORTINA OSCURA DE FONDO === */
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      /* Tinte oscuro */
      /* Desenfoque del fondo */
      inset: 0;
      z-index: 998;
    }

    body {
      font-family: Arial, sans-serif;
      background-color: #121212 !important;
      color: #fff;
      margin: 0;
      padding: 0;
    }

    .form-container {
      backdrop-filter: blur(12px);
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      margin: 70px auto;
      width: 90%;
      max-width: 700px;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
      position: relative;
      z-index: 999;
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 30px;
      text-transform: uppercase;
      letter-spacing: 1px;
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
      background-color: rgba(6, 111, 43, 1);
      color: #fff;
      width: 100%;
    }

    .btn-save:hover {
      background-color: rgba(6, 111, 43, 1);
      transform: scale(1.03);
      color: #fff;
    }

    .btn-back:hover {
      transform: scale(1.05);
    }

    .password-toggle {
      position: relative;
    }

    .toggle-btn {
      position: absolute;
      right: 15px;
      top: 44%;
      background: none;
      border: none;
      color: #fff;
      cursor: pointer;
      font-size: 16px;
      transform: translateY(-50%);
    }

    .message {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
    }

    .form-container .small a {
      color: #fff;
    }
  </style>
</head>

<body>

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
        <button type="button" class="toggle-btn" id="toggleClaveBtn">
          <i class="fas fa-eye" id="toggleClaveIcon"></i>
        </button>
      </div>


      <button type="submit" class="btn btn-save" id="btnGuardar"> <i class="fas fa-save me-2"></i>Guardar Empresa</button>

      <p class="mt-4 text-center small">
        <a href="index.php?ruta=Ver_Empresas_Admin" style="color: #fff;">
          <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
      </p>
    </form>

    <p id="mensaje" class="message"></p>
  </div>

  <script src="vista/js/ApiConexion.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleClaveBtn = document.getElementById("toggleClaveBtn");
      const claveInput = document.getElementById("clave");
      const toggleIcon = document.getElementById("toggleClaveIcon");

      toggleClaveBtn.addEventListener("click", function() {
        if (claveInput.type === "password") {
          claveInput.type = "text";
          toggleIcon.classList.remove("fa-eye");
          toggleIcon.classList.add("fa-eye-slash");
        } else {
          claveInput.type = "password";
          toggleIcon.classList.remove("fa-eye-slash");
          toggleIcon.classList.add("fa-eye");
        }
      });

      document.getElementById("btnGuardar").addEventListener("click", ctrRegistrarEmpresa);
    });

    async function ctrRegistrarEmpresa() {
      const mensaje = document.getElementById("mensaje");

      Swal.fire({
        title: 'Creando Empresa...',
        text: 'Espera un momento.',
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
      mensaje.style.color = "#ffcc00";

      const token = sessionStorage.getItem("userToken");

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

      // Validación de campos vacíos
      for (const key in data) {
        if (!data[key]) {
          Swal.fire({
            icon: "warning",
            title: "⚠️ Campos incompletos",
            text: "Por favor completa todos los campos."
          });
          return;
        }
      }

      try {
        const response = await fetch(`${ApiConexion}registrarEmpresa`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            ...(token ? {
              "Authorization": "Bearer " + token
            } : {})
          },
          body: JSON.stringify(data)
        });

        // ✅ Leer la respuesta cruda
        const textResponse = await response.text();
        console.log("📥 Respuesta del servidor:", textResponse);

        // ✅ Intentar parsear a JSON
        let result;
        try {
          result = JSON.parse(textResponse);
        } catch {
          throw new Error("El servidor devolvió una respuesta no válida.");
        }

        // ✅ Validar respuesta del backend
        if (!response.ok || result.success === false) {
          const errorMsg = result.message || result.error || "Error al registrar la empresa.";
          throw new Error(errorMsg);
        }

        // ✅ Registro exitoso
        Swal.fire({
          icon: "success",
          title: "✅ Empresa registrada",
          text: result.message || "La empresa fue registrada exitosamente.",
          timer: 2000,
          showConfirmButton: false
        });

        mensaje.textContent = "✅ Empresa registrada con éxito.";
        mensaje.style.color = "#4caf50";
        document.getElementById("formEmpresa").reset();

        setTimeout(() => {
          window.location.href = "index.php?ruta=Ver_Empresas_Admin";
        }, 1800);

      } catch (error) {
        console.error("❌ Error al registrar empresa:", error);
        Swal.fire({
          icon: "error",
          title: "❌ Error",
          text: error.message || "Ocurrió un problema al registrar la empresa."
        });
        mensaje.textContent = "❌ " + (error.message || "Error desconocido.");
        mensaje.style.color = "red";
      }
    }
  </script>
</body>

</html>