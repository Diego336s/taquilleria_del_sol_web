<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Gestión Categorías</title>
  <link rel="stylesheet" href="vista/css/admin.css?v=1.0">
  <style>
    /* === CORTINA OSCURA DE FONDO === */
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: -1;
    }

    body {
      position: relative;
    }

    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 25px 30px;
      margin: 85px auto 40px;
      width: 95%;
      max-width: 1200px;
      box-shadow: 0 10px 25px rgba(255, 107, 31, 0.6);
      position: relative;
    }

    /* === TITULO Y BUSCADOR === */
    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 25px;
    }

    h1 {
      color: #fff;
      text-transform: uppercase;
      margin: 0;

    }

    /* === BOTONES FIJOS === */
    .btn-back,
    .btn-add {
      position: relative;
      top: 15px;
      background-color: #9c4012e6;
      /* Café quemado */
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 16px;
      cursor: pointer;
      font-weight: bold;
      box-shadow: 0 10px 20px rgba(255, 107, 31, 0.5);
      transition: 0.3s;
      z-index: 999;
    }

    .btn-add {
      right: 25px;
      background-color: #ff8c1a;
    }

    .btn-back {
      background: #ff6b1f;
      color: #fff;
      font-weight: bold;
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      margin: 20px;
      box-shadow: 0 4px 12px rgba(255, 107, 31, 0.4);
      transition: all 0.3s ease;
      margin-bottom: 10px;
    }

    .btn-back:hover,
    .btn-add:hover {
      transform: scale(1.05);
    }

    /* === BUSCADOR === */
    .search-box {
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 30px;
      padding: 8px 14px;
      box-shadow: 0 0 8px rgba(255, 255, 255, 0.15);
      color: #fff;

    }

    .search-box input::placeholder {
      color: #ddd;
    }

    .search-box input {
      background: transparent;
      border: none;
      outline: none;
      color: #fff;
      padding: 6px 10px;
      font-size: 15px;
      width: 220px;
    }

    .search-box i {
      color: #ffcc00;
      font-size: 18px;
      margin-right: 6px;
      color: #fff;

    }

    /* === TABLA === */
    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
      border-radius: 15px;
      overflow: hidden;
      background-color: rgba(0, 0, 0, 0.4);
      color: #fff;
    }

    thead {
      background: linear-gradient(90deg, #ff6b1f, #ffcc00);
      color: #000;
      font-weight: bold;
      text-transform: uppercase;
    }

    th,
    td {
      padding: 12px 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    tbody tr:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    /* Botones acción */
    .btn-action {
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.95rem;
      font-weight: bold;
      margin: 2px;
      padding: 6px 12px;
      color: white;
      min-width: 100px;
    }

    .btn-edit {
      background-color: #b25d2e;
    }

    .btn-edit:hover {
      background-color: #d87a45;
      transform: scale(1.05);
    }

    .btn-delete {
      background-color: #8b3a22;
    }

    .btn-delete:hover {
      background-color: #a1482b;
      transform: scale(1.05);
    }


    .controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 25px;
      /* espacio entre controles y la tabla */
      gap: 15px;
    }
  </style>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

  <!-- 📦 CONTENEDOR PRINCIPAL -->
  <div class="dashboard-container">
    <div class="table-container">


      <div class="centrar" style="width: 100%; margin-bottom: 15px;">
        <h1 style="margin: 0;">Gestión de Categorías</h1>
        <button class="btn-add" onclick="window.location.href='index.php?ruta=Crear_Categorias'">
          <i class="fa-solid fa-plus"></i> Nuevas Categorías
        </button>
      </div>

      <div class="controls">
        <button class="btn-back" onclick="volverDashboard()">⬅ Volver al Dashboard</button>

        <!-- Buscador arriba a la derecha -->
        <div style="display: flex; justify-content: flex-end; ">
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="buscador" placeholder="Buscar categoría...">
          </div>
        </div>

      </div>

      <table>
        <thead>
          <tr>
            <th>Nombre Categoría</th>
            <th>Acciones</th>
          </tr>
        </thead>

        <tbody id="tablaCategorias">
          <tr>
            <td colspan="3">Cargando categorías...</td>
          </tr>
        </tbody>

      </table>
    </div>
  </div>

  <!-- SCRIPTS -->
  <script src="vista/js/ApiConexion.js"></script>
  <script src="vista/js/Admin/Categorias/VerCategorias.js"></script>

  <script>
    function volverDashboard() {
      window.location.href = 'index.php?ruta=dashboard-admin';
    }

    document.addEventListener("DOMContentLoaded", () => {
      const tbody = document.getElementById("tablaCategorias");
      tbody.id = "tbody-categorias";

      ctrListarCategorias();

      // 🔍 FILTRO
      const buscador = document.getElementById("buscador");
      buscador.addEventListener("keyup", () => {
        const filtro = buscador.value.toLowerCase();
        const filas = document.querySelectorAll("#tbody-categorias tr");

        filas.forEach(fila => {
          fila.style.display =
            fila.textContent.toLowerCase().includes(filtro) ? "" : "none";
        });
      });
    });
  </script>

</body>

</html>