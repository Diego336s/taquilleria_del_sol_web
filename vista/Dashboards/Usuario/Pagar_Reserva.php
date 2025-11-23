<link rel="stylesheet" href="vista/css/main.css?v=1.3">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 600px; width: 100%;">
        <div class="card-body">
            <div class="text-center mb-4">
                <h1 class="h3 text-white">Finalizar Compra</h1>
                <p class="text-white-50">Estás a un paso de completar tu reserva.</p>
            </div>

            <div id="resumen-pago">
                <!-- El resumen se cargará aquí desde JavaScript -->
            </div>

            <!-- Aquí iría el formulario de pago -->
            <div class="mt-4">
                <button id="pagar" class="btn btn-success w-100 py-2">
                    <i class="fas fa-credit-card me-2"></i> Pagar de forma segura
                </button>
            </div>

            <script>
                document.getElementById("pagar").addEventListener("click", async () => {
                    try {
                        const reserva = JSON.parse(sessionStorage.getItem("reservaActual"));
                        if (!reserva) {
                            Swal.fire('Error', 'No se encontró información de la reserva para procesar el pago.', 'error');
                            return;
                        }

                        const userData = JSON.parse(sessionStorage.getItem("userData"));
                        if (!userData || !userData.id) {
                            Swal.fire('Error', 'No se encontró información del usuario. Por favor, inicia sesión de nuevo.', 'error');
                            return;
                        }

                        Swal.fire({
                            title: 'Conectando con la pasarela de pago...',
                            text: 'Por favor, espera un momento.',
                            allowOutsideClick: false,
                            background: 'rgba(10, 10, 10, 0.9)',
                            color: '#fff',
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const urlApi = `${ApiConexion}pago/stripe-web`;

                        const response = await fetch(urlApi, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                total: reserva.total,
                                asientos: reserva.id_asientos,
                                id_cliente: userData.id,
                                id_evento: reserva.evento.id
                            })
                        });

                        console.log("ids asientos", reserva.id_asientos);
                        const data = await response.json();
                        Swal.close();

                        if (data.url) {
                            window.location.href = data.url;
                        } else {
                            Swal.fire('Error', 'No se pudo crear la sesión de pago. Por favor, inténtalo de nuevo.', 'error');
                        }
                    } catch (error) {
                        Swal.close();
                        Swal.fire('Error de Conexión', 'No se pudo conectar con el servidor de pagos.', 'error');
                    }
                });
            </script>



            <p class="mt-4 text-center small">
                <a href="#" id="volver-link">
                    <i class="fas fa-arrow-left me-1"></i> Modificar selección
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const reserva = JSON.parse(sessionStorage.getItem('reservaActual'));
        const resumenDiv = document.getElementById('resumen-pago');
        const volverLink = document.getElementById('volver-link');

        if (reserva && resumenDiv) {
            let asientosHtml = reserva.asientos.map(asiento => `<li>${asiento.ubicacion} - Asiento ${asiento.numero}</li>`).join('');

            resumenDiv.innerHTML = `
            <h4 class="text-white">${reserva.evento.nombre}</h4>
            <ul class="text-white-50" style="list-style: none; padding-left: 0;">${asientosHtml}</ul>
            <h3 class="text-center text-warning mt-3">Total: $ ${reserva.total.toLocaleString('es-CO')} COP</h3>
        `;

            volverLink.href = `index.php?ruta=seleccionar_asientos&eventoid=${reserva.evento.id}`;
        } else {
            resumenDiv.innerHTML = '<p class="text-danger text-center">No se encontró información de la reserva.</p>';
        }
    });
</script>