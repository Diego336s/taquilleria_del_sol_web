<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Empresas Registradas</title>
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
            /* Cortina oscura semitransparente */
            z-index: -1;
            /* Se asegura que esté detrás de todo el contenido */
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
            letter-spacing: 1px;
            margin: 0;
        }

        /* === BOTONES FIJOS: VOLVER Y NUEVA EMPRESA === */
        .btn-back,
        .btn-add {
            position: fixed;
            top: 25px;
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
            /* Naranja */
        }

        .btn-back {
            left: 25px;
        }

        .btn-back:hover,
        .btn-add:hover {
            transform: scale(1.05);
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 30px;
            padding: 8px 14px;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.15);
            transition: 0.3s;
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

        .search-box input::placeholder {
            color: #ddd;
        }

        .search-box i {
            color: #ffcc00;
            font-size: 18px;
            margin-right: 6px;
        }

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
            transition: all 0.3s ease;
        }

        /* === BOTONES EDITAR / ELIMINAR === */
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
            /* Asegura que ambos botones tengan el mismo ancho mínimo */
            transition: all 0.3s ease;
        }

        /* 🔶 EDITAR */
        .btn-edit {
            background-color: #b25d2e;
            /* café claro / naranja quemado */
            box-shadow: 0 4px 10px rgba(178, 93, 46, 0.5);
        }

        .btn-edit:hover {
            background-color: #d87a45;
            transform: scale(1.05);
        }

        /* 🔶 ELIMINAR */
        .btn-delete {
            background-color: #8b3a22;
            /* café rojizo */
            box-shadow: 0 4px 10px rgba(139, 58, 34, 0.4);
        }

        .btn-delete:hover {
            background-color: #a1482b;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            h1 {
                font-size: 1.4rem;
            }

            .search-box input {
                width: 100%;
            }

            th,
            td {
                padding: 8px;
                font-size: 0.9rem;
            }

            .btn-action {
                padding: 4px 8px;
                font-size: 0.8rem;
            }
        }
    </style>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  

    <!-- 📦 Contenedor principal -->
    <div class="dashboard-container">
        <div class="header-section">
            <h1><i class="fa-solid fa-building"></i>Eventos Finalizados</h1>

            <!-- 🔍 Buscador -->
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="buscador" placeholder="Buscar empresa...">
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Asientos Vendidos</th>
                        <th>Dinero Generado</th>
                        <th>Ocupación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaEventos">
                    <tr>
                        <td colspan="8">Cargando eventos...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="vista\js\Empresa\EventosRealizados.js"></script>



</body>

</html>