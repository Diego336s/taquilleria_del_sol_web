<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil del Administrador</title>
  <link rel="stylesheet" href="../../../css/main.css?v=1.1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a2d9b6e73f.js" crossorigin="anonymous"></script>

  <style>
    body {
      background: url('../../css/img/fondo.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Poppins', sans-serif;
      color: #fff;
      margin: 0;
    }

    .login-card {
      background: rgba(0, 0, 0, 0.65);
      border-radius: 20px;
      backdrop-filter: blur(8px);
    }

    .form-label {
      color: #fff;
      font-weight: 600;
    }

    .form-control, .form-select {
      background-color: rgba(255, 255, 255, 0.2);
      color: #fff;
      border: none;
      border-radius: 8px;
    }

    .form-control:focus, .form-select:focus {
      background-color: rgba(255, 255, 255, 0.3);
      box-shadow: none;
    }

    .btn-success {
      background-color: #ff6b1f;
      border: none;
      font-weight: bold;
      transition: 0.3s;
    }

    .btn-success:hover {
      background-color: #e95d15;
      transform: scale(1.05);
    }

    a {
      color: #ff6b1f;
      text-decoration: none;
      font-weight: 500;
    }

    a:hover {
      text-decoration: underline;
    }

    .login-logo {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.2);
      padding: 10px;
    }
  </style>
</head>

<body>
  <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 700px; width: 100%;">
      <div class="card-body">

        <div class="text-center mb-4">
          <img src="https://cdn-icons-png.flaticon.com/512/1077/1077114.png" 
               alt="Icono de Perfil" 
               class="login-logo mb-3" 
               id="profile_icon">
          <h1 class="h3 text-white">Perfil del Administrador</h1>
          <p class="text-white-50">Gestiona tu información personal y la configuración de tu cuenta.</p>
        </div>

        <form id="form_actualizar_perfil">
          <div class="row">
            <!-- Columna Izquierda -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="profile_nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="profile_nombre" name="nombre" placeholder="Tu nombre" required>
              </div>
              <div class="mb-3">
                <label for="profile_apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="profile_apellido" name="apellido" placeholder="Tu apellido" required>
              </div>
              <div class="mb-3">
                <label for="profile_documento" class="form-label">Documento</label>
                <input type="text" class="form-control" id="profile_documento" name="documento" placeholder="Tu documento" readonly>
                <small class="text-white-50">No se puede modificar.</small>
              </div>
            </div>

            <!-- Columna Derecha -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="profile_correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="profile_correo" name="correo" placeholder="tu@correo.com" readonly>
                <small class="text-white-50">No se puede modificar.</small>
              </div>
              <div class="mb-3">
                <label for="profile_telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="profile_telefono" name="telefono" placeholder="Tu teléfono">
              </div>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-success w-100 py-2">
              <i class="fas fa-save me-2"></i>Guardar Cambios
            </button>
          </div>
        </form>

        <hr class="my-4" style="border-color: rgba(255,255,255,0.2);">

        <div class="text-center">
          <a href="../Administrador/Dashboard_Admin.php" class="me-3">
            <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
          </a>
          <a href="/taquilleria_del_sol_web/index.php" onclick="cerrarSesion()">
            <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
          </a>
        </div>

      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const userDataString = sessionStorage.getItem('userData');

      if (userDataString) {
        try {
          const profileIcon = document.getElementById('profile_icon');
          const userData = JSON.parse(userDataString);

          // Cambiar el ícono según el sexo
          if (userData.sexo === 'M') {
            profileIcon.src = 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';
          } else if (userData.sexo === 'F') {
            profileIcon.src = 'https://cdn-icons-png.flaticon.com/512/3135/3135789.png';
          }

          // Rellenar los campos del formulario
          document.getElementById('profile_nombre').value = userData.nombres || '';
          document.getElementById('profile_apellido').value = userData.apellidos || '';
          document.getElementById('profile_documento').value = userData.documento || '';
          document.getElementById('profile_correo').value = userData.correo || '';
          document.getElementById('profile_telefono').value = userData.telefono || '';
        } catch (e) {
          console.error('Error al cargar datos del perfil:', e);
        }
      }
    });

    // Guardar cambios (lógica de simulación, lista para conectar al backend)
    document.getElementById('form_actualizar_perfil').addEventListener('submit', (e) => {
      e.preventDefault();
      alert('✅ Cambios guardados correctamente.');
    });

    // Cerrar sesión
    function cerrarSesion() {
      sessionStorage.clear();
      localStorage.clear();
      alert("Sesión cerrada correctamente.");
    }
  </script>
</body>
</html>
