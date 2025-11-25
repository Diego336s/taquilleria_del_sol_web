document.addEventListener("DOMContentLoaded", async function () {
    await contadorDeEventosActivos();   
    await totalVendidoEsteAño(); 
    await totalEventosRealizados();
    await totalAsientosVendidos();
    await crecimientoMensualDeLaEmpresa();
});




// =========================================================================
// FUNCIÓN: CONTADOR DE EVENTOS ACTIVOS
// =========================================================================

async function contadorDeEventosActivos() {
    console.log("Dentro a la funcion");
    const userDataString = sessionStorage.getItem('userData');
    const token = sessionStorage.getItem('userToken');

    if (!userDataString || !token) {
        console.error("No se encontró la información del usuario o el token.");
        return
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    if (!empresa_id) {
        console.error("No se encontró el ID de la empresa.");
        return
    }


    // ELEMENTOS
    const lugarContador = document.getElementById('contendorCantidadCantidadEventosActivos');
    const loader = document.getElementById('loaderEventosActivos');

    try {
        // 🔥 Mostrar loader
        loader.style.display = "block";
        lugarContador.innerHTML = ""; // limpia el contenedor

        // Llamado al servidor
        const response = await fetch(ApiConexion + "contador/obras-activas/" + empresa_id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            }
        });

        const data = await response.json();

        // Ocultar loader
        loader.style.display = "none";

        // Crear número
        const span = document.createElement("span");
        span.className = "widget-number";
        span.textContent = data.success ? data.obras_activas : 0;

        lugarContador.append(span);

    } catch (error) {
        console.error("Error:", error);

        // Ocultar loader en caso de error
        loader.style.display = "none";

        lugarContador.innerHTML = "<span class='widget-number'>0</span>";
    }
}



// =========================================================================
// FUNCIÓN: TOTAL VENDIDO ESTE AÑO
// =========================================================================

async function totalVendidoEsteAño() {
  
    const userDataString = sessionStorage.getItem('userData');
    const token = sessionStorage.getItem('userToken');

    if (!userDataString || !token) {
        console.error("No se encontró la información del usuario o el token.");
        return
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    if (!empresa_id) {
        console.error("No se encontró el ID de la empresa.");
        return
    }


    // ELEMENTOS
    const lugarContador = document.getElementById('contendorTotalVendido');
    const loader = document.getElementById('loaderTotalVendido');

    try {
        // 🔥 Mostrar loader
        loader.style.display = "block";
        lugarContador.innerHTML = ""; // limpia el contenedor

        // Llamado al servidor
        const response = await fetch(ApiConexion + "total-vendido-empresa-año/" + empresa_id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            }
        });

        const data = await response.json();

        // Ocultar loader
        loader.style.display = "none";

        // Crear número
        const span = document.createElement("span");
        span.className = "widget-number";
        span.textContent = data.success ? data.total_vendido : 0;

        lugarContador.append(span);

    } catch (error) {
        console.error("Error:", error);

        // Ocultar loader en caso de error
        loader.style.display = "none";

        lugarContador.innerHTML = "<span class='widget-number'>0</span>";
    }
}



// =========================================================================
// FUNCIÓN: TOTAL EVENTOS REALIZADOS
// =========================================================================

async function totalEventosRealizados() {
   
    const userDataString = sessionStorage.getItem('userData');
    const token = sessionStorage.getItem('userToken');

    if (!userDataString || !token) {
        console.error("No se encontró la información del usuario o el token.");
        return
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    if (!empresa_id) {
        console.error("No se encontró el ID de la empresa.");
        return
    }


    // ELEMENTOS
    const lugarContador = document.getElementById('contendorEventosRealizados');
    const loader = document.getElementById('loaderEventosRealizados');

    try {
        // 🔥 Mostrar loader
        loader.style.display = "block";
        lugarContador.innerHTML = ""; // limpia el contenedor

        // Llamado al servidor
        const response = await fetch(ApiConexion + "total-eventos-realizados/" + empresa_id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            }
        });

        const data = await response.json();

        // Ocultar loader
        loader.style.display = "none";

        // Crear número
        const span = document.createElement("span");
        span.className = "widget-number";
        span.textContent = data.success ? data.total_eventos_realizados : 0;

        lugarContador.append(span);

    } catch (error) {
        console.error("Error:", error);

        // Ocultar loader en caso de error
        loader.style.display = "none";

        lugarContador.innerHTML = "<span class='widget-number'>0</span>";
    }
}


// =========================================================================
// FUNCIÓN: TOTAL ASIENTOS VENDIDOS
// =========================================================================

async function totalAsientosVendidos() {
    
    const userDataString = sessionStorage.getItem('userData');
    const token = sessionStorage.getItem('userToken');

    if (!userDataString || !token) {
        console.error("No se encontró la información del usuario o el token.");
        return
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    if (!empresa_id) {
        console.error("No se encontró el ID de la empresa.");
        return
    }


    // ELEMENTOS
    const lugarContador = document.getElementById('contendorAsientosVendidos');
    const loader = document.getElementById('loaderAsientosVendidos');

    try {
        // 🔥 Mostrar loader
        loader.style.display = "block";
        lugarContador.innerHTML = ""; // limpia el contenedor

        // Llamado al servidor
        const response = await fetch(ApiConexion + "total-asientos-vendidos/" + empresa_id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            }
        });

        const data = await response.json();

        // Ocultar loader
        loader.style.display = "none";

        // Crear número
        const span = document.createElement("span");
        span.className = "widget-number";
        span.textContent = data.success ? data.total_asientos_vendidos : 0;

        lugarContador.append(span);

    } catch (error) {
        console.error("Error:", error);

        // Ocultar loader en caso de error
        loader.style.display = "none";

        lugarContador.innerHTML = "<span class='widget-number'>0</span>";
    }
}



// =========================================================================
// FUNCIÓN: CRECIMIENTO MENSUAL DE LA EMPRESA
// =========================================================================

async function crecimientoMensualDeLaEmpresa() {
    
    const userDataString = sessionStorage.getItem('userData');
    const token = sessionStorage.getItem('userToken');

    if (!userDataString || !token) {
        console.error("No se encontró la información del usuario o el token.");
        return
    }

    const userData = JSON.parse(userDataString);
    const empresa_id = userData.id;

    if (!empresa_id) {
        console.error("No se encontró el ID de la empresa.");
        return
    }


    // ELEMENTOS
    const lugarContador = document.getElementById('contendorCrecimientoMensual');
    const loader = document.getElementById('loaderCrecimientoMensual');

    try {
        // 🔥 Mostrar loader
        loader.style.display = "block";
        lugarContador.innerHTML = ""; // limpia el contenedor

        // Llamado al servidor
        const response = await fetch(ApiConexion + "crecimiento-mensual/" + empresa_id, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Authorization": "Bearer " + token
            }
        });

        const data = await response.json();

        // Ocultar loader
        loader.style.display = "none";

        // Crear número
        const span = document.createElement("span");
        span.className = "widget-number";
        span.textContent = data.success ? data.crecimiento_mensual : 0;

        lugarContador.append(span);

    } catch (error) {
        console.error("Error:", error);

        // Ocultar loader en caso de error
        loader.style.display = "none";

        lugarContador.innerHTML = "<span class='widget-number'>0</span>";
    }
}