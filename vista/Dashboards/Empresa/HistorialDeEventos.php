<title>Historial de eventos</title>
<link rel="stylesheet" href="vista\css\Empresa\Eventos_Realizados.css">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">




  <!-- From Uiverse.io by karthik092726122003 -->
    <div class="styled-wrapper">
        <a href="index.php?ruta=dashboard-empresa" class="button">
            <div class="button-box">
                <span class="button-elem">
                    <svg
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                        class="arrow-icon">
                        <path
                            fill="#ff6b1f"
                            d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
                    </svg>
                </span>
                <span class="button-elem">
                    <svg
                        fill="#ff6b1f"
                        viewBox="0 0  24 24"
                        xmlns="http://www.w3.org/2000/svg"
                        class="arrow-icon">
                        <path
                            d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path>
                    </svg>
                </span>
            </div>
        </a>
    </div>
<!-- 📦 Contenedor principal -->
<div class="dashboard-container">
  

    <div class="header-section">
        <h1><i class="fa-solid fa-building"></i> Historial de eventos</h1>

        <!-- 🔍 Buscador -->
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="buscador" placeholder="Buscar evento...">
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