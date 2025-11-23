<!-- Enlazamos la hoja de estilos principal -->
<link rel="stylesheet" href="vista/css/main.css?v=1.3">

<style>
    .tickets-container {
        max-width: 900px;
        margin: auto;
        padding: 2rem;
        min-height: 100vh;
    }

    .page-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .page-header .page-title {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ffd966;
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }

    .page-header .page-subtitle {
        font-size: 1.1rem;
        color: #e0e0e0;
    }

    .ticket-card {
        background: linear-gradient(145deg, rgba(45, 45, 45, 0.7), rgba(30, 30, 30, 0.8));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 215, 0, 0.25);
        border-radius: 15px;
        margin-bottom: 2rem;
        display: flex;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ticket-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(255, 215, 0, 0.15);
    }

    .ticket-main {
        padding: 1.5rem 2rem;
        flex-grow: 1;
    }

    .ticket-event-title {
        font-size: 1.6rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
    }

    .ticket-details {
        list-style: none;
        padding: 0;
        margin: 0;
        color: #ccc;
    }

    .ticket-details li {
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }

    .ticket-details i {
        color: #ffd966;
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .ticket-actions {
        margin-top: 1.5rem;
        display: flex;
        gap: 1rem;
    }

    .btn-ticket-action {
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #fff;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-ticket-action:hover {
        background-color: #ffd966;
        color: #111;
    }

    .ticket-stub {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 180px;
        position: relative;
        border-left: 2px dashed rgba(255, 215, 0, 0.3);
    }

    .ticket-stub::before,
    .ticket-stub::after {
        content: '';
        position: absolute;
        left: -11px;
        width: 20px;
        height: 20px;
        background: #121212; /* Debe coincidir con el fondo del contenedor principal */
        border-radius: 50%;
    }

    .ticket-stub::before {
        top: -10px;
    }

    .ticket-stub::after {
        bottom: -10px;
    }

    .ticket-qr {
        width: 120px;
        height: 120px;
        background-color: white;
        padding: 5px;
        border-radius: 5px;
    }

    .ticket-qr img {
        width: 100%;
        height: 100%;
        display: block;
    }

    .no-tickets-message {
        text-align: center;
        padding: 3rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        border: 1px solid rgba(255, 215, 0, 0.2);
    }

</style>

<div class="tickets-container">
    <div class="page-header">
        <h1 class="page-title">Mis Tickets</h1>
        <p class="page-subtitle">Aquí encontrarás todas tus entradas para los próximos eventos.</p>
    </div>

    <!-- Ejemplo de un Ticket -->
    <div class="ticket-card">
        <div class="ticket-main">
            <h2 class="ticket-event-title">El Fantasma de la Ópera</h2>
            <ul class="ticket-details">
                <li><i class="fas fa-calendar-alt"></i> 25 de Diciembre, 2024</li>
                <li><i class="fas fa-clock"></i> 20:00 PM</li>
                <li><i class="fas fa-chair"></i> Palco Izquierdo, Asiento P-12</li>
            </ul>
            <div class="ticket-actions">
                <button class="btn btn-sm btn-ticket-action"><i class="fas fa-download me-2"></i>Descargar PDF</button>
                <button class="btn btn-sm btn-ticket-action"><i class="fas fa-info-circle me-2"></i>Ver Detalles</button>
            </div>
        </div>
        <div class="ticket-stub">
            <div class="ticket-qr">
                <!-- Aquí iría el QR real. Usamos un placeholder. -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TicketID-12345" alt="Código QR">
            </div>
        </div>
    </div>

    <!-- Ejemplo de otro Ticket -->
    <div class="ticket-card">
        <!-- Contenido similar al anterior -->
    </div>

    <!-- Mensaje para cuando no hay tickets -->
    <div class="no-tickets-message">
        <h3 class="text-white">Aún no tienes tickets</h3>
        <p class="text-white-50">Parece que todavía no has comprado entradas para ningún evento. ¡Explora nuestra cartelera!</p>
    </div>

    <p class="mt-5 text-center small">
        <a href="index.php?ruta=dashboard-usuario">
            <i class="fas fa-arrow-left me-1"></i> Ver cartelera
        </a>
    </p>
</div>

<script>
    // Aquí podrías agregar la lógica para cargar dinámicamente los tickets del usuario desde la API.
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Página de Mis Tickets cargada.');
        // Ejemplo: fetch(`${ApiConexion}tickets/usuario/${userId}`)
    });
</script>

<!-- Cargamos primero la conexión a la API -->
<script src='vista\js\Usuario\Mis_Tickets.js'></script> <!-- Lógica del dashboard de usuario -->