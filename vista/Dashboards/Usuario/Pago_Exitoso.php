<link rel="stylesheet" href="vista/css/main.css?v=1.3">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div id="payment-status-card" class="card p-4 shadow login-card text-center" style="max-width: 500px; width: 100%;">
        <!-- El contenido se generará dinámicamente -->
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {

    const card = document.getElementById("payment-status-card");

    // Tomamos la RUTA COMPLETA
    const path = window.location.pathname.split("/");

    // ÚLTIMOS 4 SEGMENTOS ↓↓↓
    const evento      = path.pop(); 
    const total       = path.pop(); 
    const cliente     = path.pop(); 
    const asientosEnc = path.pop(); 

    console.log({asientosEnc, cliente, total, evento});

    // Estado inicial
    card.innerHTML = `
        <div class="card-body">
            <div class="spinner-border text-warning mb-3" role="status"></div>
            <h1 class="h4 text-white">Confirmando tu compra...</h1>
            <p class="text-white-50">Por favor, no cierres ni recargues esta página.</p>
        </div>
    `;

    if (!asientosEnc || !cliente || !total || !evento) {
        card.innerHTML = `
            <div class="card-body">
                <img src="https://cdn-icons-png.flaticon.com/512/7465/7465691.png" class="login-logo mb-3">
                <h1 class="h4 text-danger">Error en la Confirmación</h1>
                <p class="text-white-50">Faltan parámetros para validar tu compra.</p>
                <a href="index.php?ruta=dashboard-usuario" class="btn btn-primary mt-3">Volver</a>
            </div>
        `;
        return;
    }

    try {
        const urlApi = `${ApiConexion}pago-exitoso/${asientosEnc}/${cliente}/${total}/${evento}`;
        const response = await fetch(urlApi);
        const data = await response.json();

        if (response.ok && data.success) {
            sessionStorage.removeItem("reservaActual");

            card.innerHTML = `
                <div class="card-body">
                    <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" class="login-logo mb-3">
                    <h1 class="h4 text-success">¡Compra Exitosa!</h1>
                    <p class="text-white-50">${data.message}</p>
                    <a href="index.php?ruta=dashboard-usuario" class="btn btn-success mt-3">Ir a mis compras</a>
                </div>
            `;
        } else {
            throw new Error(data.message);
        }

    } catch (error) {
        card.innerHTML = `
            <div class="card-body">
                <img src="https://cdn-icons-png.flaticon.com/512/463/463612.png" class="login-logo mb-3">
                <h1 class="h4 text-danger">Hubo un Problema</h1>
                <p class="text-white-50">${error.message}</p>
                <a href="index.php?ruta=dashboard-usuario" class="btn btn-primary mt-3">Volver</a>
            </div>
        `;
    }
});

</script>
