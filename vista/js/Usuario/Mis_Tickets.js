
document.addEventListener('DOMContentLoaded', () => {

    // Verificar Autenticación y Redirigir (Protección de Rutas)
    checkAuthAndRedirect();

    // Poblar datos del usuario en la UI (ej. saludo en dashboard)
    populateUserData();

    //Cargar los tickets del usuario
    cargarMisTickets();

});
async function cargarMisTickets() {

    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    if (!token || !userDataString) {
        console.warn("No hay sesión activa.");
        return;
    }

    const userData = JSON.parse(userDataString);
    const userId = userData.id;

    const contenedor = document.querySelector(".tickets-container");

    if (!contenedor) {
        console.warn("No se encontró el contenedor .tickets-container");
        return;
    }

    // Mostrar cargando temporal
    contenedor.innerHTML = `
    <div class="no-tickets-message">
        <h3 class="text-white">Cargando tickets...</h3>
    </div>
`;
    const urlAPI = `${ApiConexion}mis-tickets/cliente/${userId}`;
    try {
        const respuesta = await fetch(urlAPI, {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
                "Content-Type": "application/json"
            }
        });

        const data = await respuesta.json();
        console.log("Respuesta API:", data);

        if (!data.success || !data.tickets || data.tickets.length === 0) {

            contenedor.innerHTML = `
            <div class="no-tickets-message">
                <h3 class="text-white">Aún no tienes tickets</h3>
                <p class="text-white-50">
                    Parece que todavía no has comprado entradas para ningún evento.
                </p>
                <a href="index.php?ruta=dashboard-usuario" class="btn btn-success mt-3">
                    Ver Cartelera
                </a>
            </div>
        `;
            return;
        }

        // Si hay tickets
        contenedor.innerHTML = `
    <div class="page-header">
        <h1 class="page-title">Mis Tickets</h1>
        <p class="page-subtitle">Aquí encontrarás todas tus entradas para los próximos eventos.</p>
    </div>
    `;

        data.tickets.forEach(ticket => {

            // Asientos unidos en una sola cadena
            const asientosTexto = ticket.asientos
                .map(a => `${a.ubicacion} - Fila ${a.fila} - Silla ${a.numero}`)
                .join("<br>");


            // QR temporal usando el ID del ticket
            const qrURL = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=Ticket-${ticket.ticket_id}`;

            contenedor.innerHTML += `
            <div class="ticket-card">
                <div class="ticket-main">
                    <h2 class="ticket-event-title">${ticket.titulo}</h2>
                    <ul class="ticket-details">
                        <li><i class="fas fa-calendar-alt"></i> ${ticket.fecha_evento}</li>
                        <li><i class="fas fa-clock"></i> ${ticket.hora_inicio}</li>
                        <li>
    <i class="fas fa-chair"></i>
    <span>${asientosTexto}</span>
</li>

                    </ul>
                    <div class="ticket-actions">
                        <button class="btn btn-sm btn-ticket-action" onclick="descargarTicketPDF(${ticket.id})">
                            <i class="fas fa-download me-2"></i>Descargar PDF
                        </button>
                        <button class="btn btn-sm btn-ticket-action" onclick="verDetallesTicket(${ticket.ticket_id})">
                            <i class="fas fa-info-circle me-2"></i>Ver Detalles
                        </button>
                    </div>
                </div>
                <div class="ticket-stub">
                    <div class="ticket-qr">
                        <img src="${ticket.qr}" alt="QR Ticket">

                    </div>
                </div>
            </div>
        `;

        });

        // Agregar botón volver
        contenedor.innerHTML += `
        <p class="mt-5 text-center small">
            <a href="index.php?ruta=dashboard-usuario">
                <i class="fas fa-arrow-left me-1"></i> Volver al Dashboard
            </a>
        </p>
    `;

    } catch (e) {
        console.error("Error cargando tickets:", e);
        contenedor.innerHTML = `
        <div class="no-tickets-message">
            <h3 class="text-white">Error al cargar tickets</h3>
        </div>
    `;
    }

}


async function descargarTicketPDF(ticketId) {
    const token = sessionStorage.getItem('userToken');

    if (!token) {
        alert("Debes iniciar sesión.");
        return;
    }

    const url = `${ApiConexion}ticket/pdf/${ticketId}`;

    try {
        const response = await fetch(url, {
            method: "GET",
            headers: {
                "Authorization": "Bearer " + token,
            }
        });

        if (!response.ok) {
            throw new Error("Error descargando el PDF");
        }

        // Convertir la respuesta en un blob PDF
        const blob = await response.blob();

        // Crear enlace de descarga
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `ticket-${ticketId}.pdf`;
        document.body.appendChild(link);
        link.click();
        link.remove();

    } catch (error) {
        console.error("Error:", error);
        alert("No se pudo descargar el PDF.");
    }
}


