<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow login-card" style="max-width: 550px; width: 100%;">
        <div class="card-body text-center">

            <!-- Puedes usar un ícono o una imagen temática -->
            <img src="https://cdn-icons-png.flaticon.com/512/7465/7465691.png" alt="Máscara de teatro rota" class="login-logo mb-4 mx-auto d-block" style="width: 100px; height: 100px;">

            <h1 class="display-1 fw-bold text-white">404</h1>

            <h2 class="card-title mb-3 text-white">¡Ups! Página no encontrada</h2>

            <p class="text-white-50 mb-4">
                Parece que te has perdido en el backstage. La página que buscas no existe o ha sido movida.
            </p>

            <a href="#" onclick="volverInicio()" class="btn btn-primary w-75 py-2">
                <i class="fas fa-home me-2"></i>
                Volver al Inicio
            </a>




        </div>
    </div>
</div>


<script>
function volverInicio() {
    const token = sessionStorage.getItem('userToken');
    const userDataString = sessionStorage.getItem('userData');

    // 🚫 Si estamos en la vista 404 → siempre enviar al login sin importar sesión
    const currentRuta = new URLSearchParams(window.location.search).get("ruta");
    if (currentRuta === "404") {
        sessionStorage.removeItem("ultimaRuta"); // Limpieza opcional
        window.location.replace("index.php?ruta=login");
        return;
    }

    // 🔹 Si no hay sesión → login directo
    if (!token || !userDataString) {
        window.location.replace("index.php?ruta=login");
        return;
    }

    try {
        const userData = JSON.parse(userDataString);
        const rol = (userData.rol || userData.role || userData.tipo || "").toLowerCase();

        // 🔸 Redirigir según el rol (por si algún otro botón usa esta función)
        if (rol.includes('empresa')) {
            window.location.replace("index.php?ruta=dashboard-empresa");
        } else if (rol.includes('admin')) {
            window.location.replace("index.php?ruta=dashboard-admin");
        } else {
            window.location.replace("index.php?ruta=dashboard-usuario");
        }
    } catch (e) {
        console.error("Error leyendo userData:", e);
        sessionStorage.clear();
        window.location.replace("index.php?ruta=login");
    }
}
</script>
