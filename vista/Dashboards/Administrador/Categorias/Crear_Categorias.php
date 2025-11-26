<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Registrar Nueva Categoría</title>
  <link rel="stylesheet" href="vista/css/admin.css?v=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
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
      max-width: 600px;
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
      width: 100%;
    }

    .btn-save {
      background-color: rgba(6, 111, 43, 1);
      color: #fff;
    }

    .btn-save:hover {
      transform: scale(1.03);
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
    <h1>Registrar Nueva Categoría</h1>

    <form id="formCategoria">
      <label>Nombre de la Categoría</label>
      <input type="text" id="nombre" placeholder="Ej: Teatro, Música, Comedia..." required>

      <button type="submit" class="btn btn-save" id="btnGuardar">
        <i class="fas fa-save me-2"></i>Guardar Categoría
      </button>

      <p class="mt-4 text-center small">
        <a href="index.php?ruta=Ver_Categorias_Admin">
          <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
      </p>
    </form>

    <p id="mensaje" class="message"></p>
  </div>

  <script src="vista/js/ApiConexion.js"></script>

  <script>
    document.getElementById("formCategoria").addEventListener("submit", ctrRegistrarCategoria);

    async function ctrRegistrarCategoria(e) {
      e.preventDefault();

      const mensaje = document.getElementById("mensaje");
      const nombre = document.getElementById("nombre").value.trim();
      const token = sessionStorage.getItem("userToken");

      if (!nombre) {
        Swal.fire("Campos incompletos", "Ingresa un nombre para la categoría", "warning");
        return;
      }

      Swal.fire({
        title: 'Creando Categoría...',
        text: 'Espera un momento.',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      try {
        const response = await fetch(`${ApiConexion}registrarCategoria`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            ...(token ? { "Authorization": "Bearer " + token } : {})
          },
          body: JSON.stringify({ nombre })
        });

        const textResponse = await response.text();
        console.log("📥 Respuesta del servidor:", textResponse);

        let result;
        try {
          result = JSON.parse(textResponse);
        } catch {
          throw new Error("El servidor respondió con un formato no válido.");
        }

        if (!response.ok || result.success === false) {
          throw new Error(result.message || "Error al registrar la categoría.");
        }

        Swal.fire({
          icon: "success",
          title: "Categoría registrada",
          text: result.message || "La categoría fue registrada exitosamente.",
          timer: 1800,
          showConfirmButton: false
        });

        mensaje.textContent = "Categoría registrada correctamente.";
        mensaje.style.color = "#4caf50";

        document.getElementById("formCategoria").reset();

        setTimeout(() => {
          window.location.href = "index.php?ruta=Ver_Categorias_Admin";
        }, 1500);

      } catch (error) {
        console.error("❌ Error al registrar categoría:", error);
        Swal.fire("Error", error.message, "error");
        mensaje.textContent = "❌ " + error.message;
        mensaje.style.color = "red";
      }
    }
  </script>

</body>
</html>
