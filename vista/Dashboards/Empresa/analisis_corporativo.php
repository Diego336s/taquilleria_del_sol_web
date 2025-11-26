<link rel="stylesheet" href="vista/css/Empresa/AnalisisCorporativo.css">
<div class="dashboard">
    <!-- Encabezado -->
    <header class="top-bar">
        <div class="left">
            <i class="bi bi-bar-chart-line-fill logo"></i>
            <div class="title-group">
                <h1>Analisis corporativo </h1>
            </div>
        </div>
        
    </header>



    <main class="grid">
        <!-- Inversión reciente -->
        <div class="card largo">
            <h3>Entradas mensuales 💰</h3>
             <div id="loaderEntradasMesuales" class="centraLoader">
                <div class="custom-loader-estadistica"></div>
            </div>
            <div id="contenedorEntradasMensuales" class="progress-group">
            </div>
            <h3 class="tituloIngresosTotales">Ingresos total</h3>
             <div id="loaderIngresosTotales" class="centraLoader">
                <div class="custom-loader-estadistica"></div>
            </div>
            <div id="contenedorIngresosTotales">

            </div>
        </div>


        <!-- Últimos eventos -->
        <div class="card largo">
            <h3>Últimos Eventos</h3>
            <div id="loaderUltimosEventos" class="centraLoader">
                <div class="custom-loader-estadistica"></div>
            </div>
            <div id="contenedorUltimosEventos">

            </div>

        </div>


    </main>
    <p class="mt-4 text-center small">
        <a href="index.php?ruta=dashboard-empresa">
            <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
        </a>
    </p>
</div>

<script src="vista\js\Empresa\AnalisisCorporativo.js"></script>