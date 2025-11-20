<!-- Enlazamos la hoja de estilos específica para este dashboard -->
<link rel="stylesheet" href="vista/css/empresa.css?v=1.0">

<!-- Usamos el mismo ID para heredar el fondo y estilos base -->
<div class="dashboard-container" id="dashboard-usuario-body">
  <header class="dashboard-header">
    <div class="user-greeting">
      <span class="icon-circle">⚙️</span>
      <span class="welcome-text">¡Bienvenido, <strong id="nombreAdmin"></strong></span>
    </div>
    <div class="header-actions">
      <a href="index.php?ruta=Configuracion_admin" class="btn btn-explore">
        <i class="fas fa-user-circle me-2"></i>Configuracion
      </a>
      <a href="index.php?ruta=mi_perfil_admin" class="btn btn-explore">
        <i class="fas fa-user-circle me-2"></i>Mi Perfil
      </a>
            <a href="index.php?ruta=Reservas" class="btn btn-explore">
        <i class="fas fa-user-circle me-2"></i>resevas
      </a>
      <button class="btn btn-profile" onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Reportes.php'">
        <span class="icon-inline">📊</span> Reportes
      </button>
      <button class="btn btn-profile" id="btnLogoutEmpresa" onclick="confirmLogoutAdmin()">
        <span class="icon-inline">🚪</span> Cerrar Sesión
      </button>
    </div>
  </header>

  <main class="dashboard-main">

    <!-- ======= Columna Izquierda (Widgets de Empresa) ======= -->
    <aside class="summary-widgets">

      <!-- === WIDGET USUARIOS ACTIVOS === -->
<aside class="summary-widgets">
  <div class="widget">
    <div class="widget-icon green-bg">👥</div>
    <div class="widget-content">
      <span id="usuariosActivos" class="widget-number">—</span>
      <span class="widget-title">Usuarios registrados</span>
      <button class="btn btn-confirm btn-full-width orange-bg"
        onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Usuarios/Ver_Usuarios.php'">
        👥 Ver Todos los Usuarios
      </button>
    </div>
  </div>
</aside>

<script>
document.addEventListener("DOMContentLoaded", async () => {
  const usuariosElement = document.getElementById("usuariosActivos");
  usuariosElement.textContent = "—"; // temporal mientras carga

  try {
    const response = await fetch("http://localhost:8000/api/listarClientes", {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "Authorization": `Bearer ${
          localStorage.getItem("tokenCliente") || localStorage.getItem("token")
        }`,
      },
    });

    if (!response.ok) throw new Error(`Servidor respondió con ${response.status}`);

    const data = await response.json();

    // ⬇️⬇️ ESTA ES LA CLAVE — TU API DEVUELVE "clientes"
    let totalUsuarios = Array.isArray(data.clientes) ? data.clientes.length : 0;

    usuariosElement.textContent = totalUsuarios.toLocaleString();
  } catch (error) {
    console.error("❌ Error al obtener usuarios:", error);
    usuariosElement.textContent = "—";
  }
});
</script>

</style>


      <!-- === WIDGET EMPRESAS === -->
      <div class="widget">
        <div class="widget-icon">🏢</div>
        <div class="widget-content">
          <span id="totalEmpresas" class="widget-number">0</span>
          <span class="widget-title">Empresas Registradas</span>
          <button class="btn btn-confirm" onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/Empresas/Ver_Empresas.php'">
            ver todas las empresas 
          </button>
        </div>
      </div>

      <!-- === SCRIPT DE DATOS DINÁMICOS === -->
      <script>
        const API_URL = "http://127.0.0.1:8000/api";

        // 🔹 Cargar cantidad de usuarios activos
        async function cargarUsuariosActivos() {
          try {
            const res = await fetch(`${API_URL}/listarUsuarios`);
            const data = await res.json();

            if (res.ok && Array.isArray(data.data)) {
              const usuarios = data.data;
              const totalUsuarios = usuarios.length;
              const activos = usuarios.filter(u => u.estado === "Activo").length;

              document.getElementById("usuariosActivos").textContent = activos.toLocaleString();

              const porcentaje = totalUsuarios > 0 ? (activos / totalUsuarios) * 100 : 0;
              document.getElementById("usuariosProgress").style.width = `${porcentaje}%`;
            } else {
              document.getElementById("usuariosActivos").textContent = "—";
            }
          } catch (error) {
            console.error("Error al cargar usuarios activos:", error);
            document.getElementById("usuariosActivos").textContent = "⚠️";
          }
        }

        // 🔹 Cargar cantidad total de empresas
        async function cargarTotalEmpresas() {
          try {
            const response = await fetch(`${API_URL}/listarEmpresas`);
            const data = await response.json();

            if (response.ok && Array.isArray(data.data)) {
              document.getElementById("totalEmpresas").textContent = data.data.length;
            } else {
              document.getElementById("totalEmpresas").textContent = "—";
            }
          } catch (error) {
            console.error("Error al obtener empresas:", error);
            document.getElementById("totalEmpresas").textContent = "⚠️";
          }
        }

        // 🚀 Ejecutar todo al cargar la página
        document.addEventListener("DOMContentLoaded", () => {
          cargarUsuariosActivos();
          cargarTotalEmpresas();
        });
      </script>

      <!-- === WIDGET ESTADO DEL SISTEMA === -->
      <div class="widget">
        <div class="widget-content">
          <span class="widget-title h3 centrar">⚙️ Configuración del sistema</span>
          <div class="header-actions">
             <div class="item-details">
                <span class="item-title">Precios de Entradas</span>
              </div>
              <div class="item-details">
                <span class="item-title">Horarios de Función</span>
                <span class="item-title">Configurar</span>
              </div>
          <button class="btn btn-confirm btn-full-width orange-bg"
            onclick="window.location.href='/taquilleria_del_sol_web/vista/Dashboards/Administrador/PanelConfiguración.php'">
            ⚙️ Panel de Configuración
          </button>
          </div>
        </div>
      </div>
    </aside>

    <!-- ======= Columna Derecha (Contenido de Empresa) ======= -->
    <section class="content-area">
      <div class="stats-grid">
        <div class="widget">
          <div class="widget-icon">💲</div>
          <div class="widget-content">
            <span class="widget-number">$2.4M</span>
            <span class="widget-title">Ingresos mes</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">📈</div>
          <div class="widget-content">
            <span class="widget-number">156</span>
            <span class="widget-title">Funciones</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">👁️</div>
          <div class="widget-content">
            <span class="widget-number">8,945</span>
            <span class="widget-title">Entradas vendidas</span>
          </div>
        </div>
        <div class="widget">
          <div class="widget-icon">📈</div>
          <div class="widget-content">
            <span class="widget-number">94%</span>
            <span class="widget-title">Ocupación</span>
          </div>
        </div>
      </div>

      <div class="billboard-header"></div>
      <div class="widget recent-reservations-card">
        <div class="widget-header">
          <h3 class="section-title" style="margin: 0; color: #fff;">📌 Actividad Reciente del Sistema</h3>
          <button class="btn btn-confirm">Ver Todo</button>
        </div>

        <div class="reservation-list">
          <div class="reservation-item">
            <div class="item-details">
              <span class="item-title">Nueva empresa registrada: <b>“Corporación ABC”</b></span>
              <span class="item-title activity-time">Hace 15 minutos</span>
            </div>
          </div>

          <div class="reservation-item info">
            <div class="item-details">
              <span class="item-title">Backup automático completado exitosamente</span>
              <span class="item-title activity-time">Hace 1 hora</span>
            </div>
          </div>

          <div class="reservation-item">
            <div class="item-details">
              <span class="item-title">Actualización de precios aplicada</span>
              <span class="item-title activity-time">Hace 2 horas</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
</div>
