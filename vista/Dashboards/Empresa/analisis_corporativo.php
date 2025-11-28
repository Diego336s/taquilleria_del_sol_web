<link rel="stylesheet" href="vista/css/Empresa/AnalisisCorporativo.css">
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
   
</div>

<script src="vista\js\Empresa\AnalisisCorporativo.js"></script>