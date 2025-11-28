<title>Mis Tickets</title>


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
        color: #ffffffff;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .page-header .page-subtitle {
        font-size: 1.1rem;
        color: #e0e0e0;
        text-shadow: 0 0 12px rgba(0, 0, 0, 0.5);

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
        width: 230px;
        position: relative;
        border-left: 2px dashed rgba(255, 215, 0, 0.3);
    }

    .ticket-stub::before,
    .ticket-stub::after {
        content: '';
        position: absolute;
        left: -11px;
        width: 20px;
        height: 10px;
        background: #121212;
        /* Debe coincidir con el fondo del contenedor principal */
        border-radius: 50%;
    }

    .ticket-stub::before {
        top: -10px;
    }

    .ticket-stub::after {
        bottom: -10px;
    }

    .ticket-qr {
        width: 220px;
        height: 250px;
        background-color: whte;
        padding: 4px;
        border-radius: 7px;
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



    /* --- MODAL OSCURO ELEGANTE --- */
    .modal-detalles {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(5px);
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .modal-detalles-content {
        background: #1f2937;
        padding: 25px;
        width: 450px;
        max-height: 90vh;
        overflow-y: auto;
        border-radius: 15px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.4);
        border: 1px solid #374151;
    }

    .modal-title {
        color: #fbbf24;
        text-align: center;
        margin-bottom: 15px;
    }

    .modal-subtitle {
        margin-top: 20px;
        color: #93c5fd;
    }

    .modal-detalles-info p {
        color: #e5e7eb;
        font-size: 15px;
        margin: 6px 0;
    }

    .modal-detalles-info strong {
        color: #60a5fa;
    }

    .modal-detalles-close {
        float: right;
        font-size: 26px;
        cursor: pointer;
        color: #fbbf24;
    }

    /* Contenedor elegante */
    .estado-container {
        margin-top: 10px;
        margin-bottom: 15px;
    }

    /* Estilo general del badge */
    .estado-ticket {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
        display: inline-block;
        color: white;
        border: 1px solid transparent;
    }

    /* ACTIVO - Verde */
    .estado-ticket.activo {
        background-color: #28a745;
        border-color: #1f8a39;
        box-shadow: 0 0 8px rgba(40, 167, 69, 0.5);
    }

    /* USADO - Rojo */
    .estado-ticket.usado {
        background-color: #dc3545;
        border-color: #b52a36;
        box-shadow: 0 0 8px rgba(220, 53, 69, 0.5);
    }

    /* Modo impresión */
    @media print {
        .estado-ticket {
            box-shadow: none;
        }
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

<!-- MODAL DETALLES TICKET -->
<div id="ticketModal" class="modal-detalles">
    <div class="modal-detalles-content">

        <span class="modal-detalles-close" onclick="cerrarModalDetalles()">&times;</span>

        <h2 class="modal-title">Detalles del Ticket</h2>

        <div class="modal-detalles-info">
            <p><strong>Evento:</strong> <span id="detEvento"></span></p>
            <p><strong>Cliente:</strong> <span id="detCliente"></span></p>
            <p><strong>Correo Electrónico:</strong> <span id="CorreoCliente"></span></p>
            <p><strong>Fecha:</strong> <span id="detFecha"></span></p>
            <p><strong>Horario:</strong> <span id="detHora"></span></p>
            <p><strong>Total Pagado:</strong> <span id="detTotal"></span></p>

            <h3 class="modal-subtitle">Asientos</h3>
            <ul id="detAsientos"></ul>
        </div>
    </div>
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