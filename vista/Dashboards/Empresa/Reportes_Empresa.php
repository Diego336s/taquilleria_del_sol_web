<link rel="stylesheet" href="vista/css/Empresa/reportes.css?v=1.1">
<div class="dashboard">
    <!-- Encabezado -->
    <header class="top-bar">
        <div class="left">
            <i class="bi bi-bar-chart-line-fill logo"></i>
            <div class="title-group">
                <h1>Reportes</h1>              
            </div>
        </div>
        <div class="right">
    
            <button class="exportar"><i class="bi bi-download"></i> Exportar</button>
        </div>
    </header>

   

    <main class="grid">
        <!-- Inversión reciente -->
        <div class="card largo">
            <h3>Entradas mensuales 💰</h3>
            <div id="contenedorEntradasMensuales" class="progress-group">               
            </div>
        </div>


        <!-- Últimos eventos -->
        <div  class="card largo">
            <h3>Últimos Eventos</h3>
            <div id="contenedorUltimosEventos" >            
         
            </div>
           
        </div>

        <!-- Resumen de costos -->
        <div  class="card corto">
           <h3>Ingresos total</h3>
           <div id="contenedorIngresosTotales">

           </div>
        </div>
    </main>
    <p class="mt-4 text-center small">
    <a href="index.php?ruta=dashboard-empresa">
        <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
    </a>
</p>
</div>

<script src="vista\js\Empresa\Reporte.js"></script>