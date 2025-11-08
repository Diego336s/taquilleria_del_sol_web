<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <title>Mi Perfil - Administrador</title>
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
       min-height: 100vh;
       display: flex;
       align-items: center;
       justify-content: center;
     }

     .dashboard-container {
       backdrop-filter: blur(10px);
       background-color: rgba(255, 255, 255, 0.95);
       border-radius: 20px;
       padding: 40px;
       width: 90%;
       max-width: 900px;
       box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
       border: 1px solid rgba(255, 255, 255, 0.2);
     }

     h1 {
       text-align: center;
       color: #333;
       margin-bottom: 30px;
       font-weight: 600;
     }

     .profile-header {
       text-align: center;
       margin-bottom: 30px;
     }

     .profile-avatar {
       width: 100px;
       height: 100px;
       border-radius: 50%;
       background: linear-gradient(135deg, #ff6b1f, #ff853d);
       display: flex;
       align-items: center;
       justify-content: center;
       margin: 0 auto 15px;
       font-size: 40px;
       color: white;
       box-shadow: 0 8px 20px rgba(255, 107, 31, 0.4);
     }

     .form-row {
       display: flex;
       gap: 20px;
       margin-bottom: 20px;
     }

     .form-group {
       flex: 1;
     }

     label {
       color: #555;
       font-weight: 600;
       display: block;
       margin-bottom: 8px;
       font-size: 14px;
     }

     .form-control {
       width: 100%;
       padding: 12px 15px;
       border-radius: 10px;
       border: 2px solid #e1e5e9;
       outline: none;
       font-size: 16px;
       background: #fff;
       color: #333;
       transition: all 0.3s ease;
     }

     .form-control:focus {
       border-color: #ff6b1f;
       box-shadow: 0 0 0 3px rgba(255, 107, 31, 0.1);
     }

     .btn {
       padding: 14px 30px;
       border: none;
       border-radius: 12px;
       cursor: pointer;
       font-weight: 600;
       font-size: 16px;
       transition: all 0.3s ease;
       margin-right: 10px;
       display: inline-block;
     }

     .btn-success {
       background: linear-gradient(135deg, #28a745, #20c997);
       color: #fff;
       box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
     }

     .btn-success:hover {
       transform: translateY(-2px);
       box-shadow: 0 12px 25px rgba(40, 167, 69, 0.6);
     }

     .btn-back {
       position: fixed;
       top: 25px;
       left: 25px;
       background-color: #9c4012e6;
       color: #fff;
       box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
       z-index: 999;
       padding: 12px 20px;
       border-radius: 10px;
       font-weight: 600;
     }

     .btn-back:hover {
       transform: scale(1.05);
     }

     .profile-info {
       background: #f8f9fa;
       padding: 25px;
       border-radius: 15px;
       margin-bottom: 30px;
       border: 1px solid #e9ecef;
     }

     .profile-info h3 {
       color: #333;
       margin-bottom: 20px;
       text-align: center;
     }

     .info-grid {
       display: grid;
       grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
       gap: 15px;
     }

     .profile-info p {
       color: #666;
       margin: 8px 0;
       font-size: 15px;
     }

     .profile-info strong {
       color: #ff6b1f;
       font-weight: 600;
     }

     .form-actions {
       text-align: center;
       margin-top: 30px;
     }
   </style>
</head>
<body>

   <!-- Botón fijo arriba -->
   <button class="btn btn-back" onclick="window.location.href='Dashboard_Admin.php'">⬅️ Volver al Dashboard</button>

   <div class="dashboard-container">

     <h1>👤 Mi Perfil de Administrador</h1>

     <!-- Información del perfil -->
     <div class="profile-info">
       <h3>Información Actual</h3>
       <div class="profile-header">
         <div class="profile-avatar" id="profile-avatar">👨‍💼</div>
       </div>
       <div class="info-grid">
         <p><strong>Nombre:</strong> <span id="profile_nombre_display">—</span></p>
         <p><strong>Apellido:</strong> <span id="profile_apellido_display">—</span></p>
         <p><strong>Documento:</strong> <span id="profile_documento_display">—</span></p>
         <p><strong>Teléfono:</strong> <span id="profile_telefono_display">—</span></p>
         <p><strong>Sexo:</strong> <span id="profile_sexo_display">—</span></p>
         <p><strong>Correo:</strong> <span id="profile_correo_display">—</span></p>
         <p><strong>Rol:</strong> <span id="profile_rol_display">—</span></p>
       </div>
     </div>

     <!-- Formulario de actualización -->
     <form id="form_actualizar_perfil">
       <div class="form-row">
         <div class="form-group">
           <label for="profile_nombre">Nombre</label>
           <input type="text" id="profile_nombre" class="form-control" required>
         </div>
         <div class="form-group">
           <label for="profile_apellido">Apellido</label>
           <input type="text" id="profile_apellido" class="form-control" required>
         </div>
       </div>

       <div class="form-row">
         <div class="form-group">
           <label for="profile_documento">Documento</label>
           <input type="text" id="profile_documento" class="form-control" required readonly>
         </div>
         <div class="form-group">
           <label for="profile_telefono">Teléfono</label>
           <input type="tel" id="profile_telefono" class="form-control" required>
         </div>
       </div>

       <div class="form-row">
         <div class="form-group">
           <label for="profile_sexo">Sexo</label>
           <select id="profile_sexo" class="form-control" required>
             <option value="">Seleccionar...</option>
             <option value="Masculino">Masculino</option>
             <option value="Femenino">Femenino</option>
             <option value="Otro">Otro</option>
           </select>
         </div>
         <div class="form-group">
           <label for="profile_correo">Correo</label>
           <input type="email" id="profile_correo" class="form-control" required readonly>
         </div>
       </div>

       <div class="form-actions">
         <button type="submit" class="btn btn-success">💾 Guardar Cambios</button>
       </div>
     </form>
   </div>

  <!-- Incluir el archivo de conexión a API -->
  <script src="../../../js/ApiConexion.js"></script>
  <!-- Incluir el archivo JS consolidado del admin -->
  <script src="../../../js/Admin/Dashboard_Admin.js"></script>

</body>
</html>