<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>📅 Vista de Reservas</title>

  <!-- ICONOS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- FLATPICKR -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

  <style>
    .dashboard-container {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.12);
      border-radius: 20px;
      padding: 30px;
      margin: 40px auto;
      width: 95%;
      max-width: 1500px;
      box-shadow: 0 10px 25px rgba(255, 107, 31, 0.6);
      display: flex;
      gap: 40px;
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
      text-transform: uppercase;
      font-weight: 700;
    }

    /* TABLA */
    table {
      width: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      border-radius: 15px;
      overflow: hidden;
      color: #fff;
      text-align: center;
      border-collapse: collapse;
    }

    thead {
      background: linear-gradient(90deg, #ff6b1f, #ffcc00);
      color: #000;
      font-weight: bold;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .btn {
      border: none;
      border-radius: 6px;
      padding: 7px 12px;
      cursor: pointer;
      font-weight: bold;
    }

    .btn-aprobar { background: #8b0000; color: #fff; }
    .btn-rechazar { background: #ff7b00; color: #fff; }

    .calendar-wrapper {
      width: 300px;
    }

    /* FECHAS OCUPADAS ROJAS */
    .flatpickr-day.ocupada {
      background: #ff0000 !important;
      color: white !important;
      border-radius: 8px;
    }

    .flatpickr-day.ocupada:hover {
      background: #cc0000 !important;
    }

  </style>

</head>

<body>

  <h1>Gestión de Reservas</h1>

  <div class="dashboard-container">

    <!-- TABLA -->
    <div class="table-container">
      <table>
        <thead>
        <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Empresa</th>
            <th>Categoría</th>
            <th>Imagen</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        </thead>

        <tbody>
          <tr>
            <td>Evento Prueba</td>
            <td>Descripción demo</td>
            <td>2025-11-24</td>
            <td>08:00</td>
            <td>10:00</td>
            <td>3</td>
            <td>2</td>
            <td><img src="/uploads/demo.jpg" width="60"></td>
            <td>Pendiente</td>
            <td>
              <button class="btn btn-aprobar">Aprobar</button>
              <button class="btn btn-rechazar">Rechazar</button>
            </td>
          </tr>
        </tbody>

      </table>
    </div>

    <!-- CALENDARIO ESTÁTICO -->
    <div class="calendar-wrapper">
      <div id="calendarioStatic"></div>
    </div>

  </div>

  <script>
    /* EJEMPLO de fechas ocupadas (CÁMBIALAS por las de tu BD) */
    const fechasOcupadas = [
      "2025-11-24",
      "2025-11-25",
      "2025-11-30"
    ];

    flatpickr("#calendarioStatic", {
      inline: true,            // ← Hace que el calendario sea estático/visible
      locale: "es",
      dateFormat: "Y-m-d",

      onDayCreate: function(dObj, dStr, fp, dayElem) {
        const fecha = dayElem.dateObj.toISOString().split("T")[0];

        if (fechasOcupadas.includes(fecha)) {
          dayElem.classList.add("ocupada");
        }
      }
    });
  </script>

</body>
</html>
