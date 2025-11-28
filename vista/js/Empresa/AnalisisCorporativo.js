document.addEventListener("DOMContentLoaded", async function () {

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

    const loaderUltimosEventos = document.getElementById('loaderUltimosEventos');
    const loaderEntradasMesuales = document.getElementById('loaderEntradasMesuales');
    const loaderIngresosTotales = document.getElementById('loaderIngresosTotales');
    loaderUltimosEventos.style.display = "block";
    loaderEntradasMesuales.style.display = "block";
    loaderIngresosTotales.style.display = "block";
    await cargarEntradasMensuales(empresa_id);
    loaderEntradasMesuales.style.display = "none";
    await cargarTotalVendido(empresa_id);
    loaderIngresosTotales.style.display = "none";
    await cargarUltimosEventos(empresa_id);
    loaderUltimosEventos.style.display = "none";
});

async function cargarTotalVendido(empresaId) {
    const res = await fetch(ApiConexion + `total-vendido-empresa-año/${empresaId}`);
    const data = await res.json();

    console.log("TOTAL AÑO:", data);


   document.getElementById("contenedorIngresosTotales").innerHTML = `     

    <div class="progress-group">
        <p>Total vendido en el año:</p>
        <div class="bar">
            <div class="fill" style="width:${data.porcentaje_meses_transcurridos}%;  background:#ff9800"></div>
        </div>
    </div>   

    <h4>Recaudo empresa: 
        <span class="total" style="color:#28a745">$${Number(data.recaudo_empresa).toLocaleString("es-CO")}</span>
    </h4>

    <h4>Recaudo teatro: 
        <span class="total" style="color:#ff6b1f">$${Number(data.recaudo_teatro).toLocaleString("es-CO")}</span>
    </h4>
     <h4>Total vendido: 
        <span class="total">$${Number(data.total_vendido).toLocaleString("es-CO")}</span>
    </h4>

    <p style="font-size:12px; color:#888; margin-top:5px;">
        (${data.porcentaje_meses_transcurridos}% del año transcurrido)
    </p>
`;


}

async function cargarEntradasMensuales(empresaId) {

    const res = await fetch(ApiConexion + `entradas-mensuales/${empresaId}`);
    const data = await res.json();

    const contenedor = document.getElementById("contenedorEntradasMensuales");

    contenedor.innerHTML = ""; // limpiar primero

    data.entradasMensuales.forEach(m => {
        let mesTexto = "";

        switch (m.mes) {
            case 1: mesTexto = "Enero"; break;
            case 2: mesTexto = "Febrero"; break;
            case 3: mesTexto = "Marzo"; break;
            case 4: mesTexto = "Abril"; break;
            case 5: mesTexto = "Mayo"; break;
            case 6: mesTexto = "Junio"; break;
            case 7: mesTexto = "Julio"; break;
            case 8: mesTexto = "Agosto"; break;
            case 9: mesTexto = "Septiembre"; break;
            case 10: mesTexto = "Octubre"; break;
            case 11: mesTexto = "Noviembre"; break;
            case 12: mesTexto = "Diciembre"; break;
            default: mesTexto = "Mes no reconocido";
        }
        // Convertir valores a número
        const totalEmpresa = Number(m.total_empresa).toLocaleString("es-CO");
        const totalTeatro = Number(m.total_teatro).toLocaleString("es-CO");




        contenedor.innerHTML += `
        <p>${mesTexto} 
            <span>
                Empresa: $${totalEmpresa.toLocaleString("es-CO")} 
                — Teatro: $${totalTeatro.toLocaleString("es-CO")}
            </span>
        </p>

        <div class="bar">
            <div class="fill" style="width:${m.porcentaje_mes}%; background:#ff9800"></div>
        </div>
      

        <br>
    `;
    });
}



async function cargarUltimosEventos(empresaId) {
    const res = await fetch(ApiConexion + `ultimos-eventos/${empresaId}`);
    const data = await res.json();

    console.log("ULTIMOS EVENTOS:", data.eventos);

    // Pintar en HTML
    const cont = document.getElementById("contenedorUltimosEventos");
    cont.innerHTML = "";
    if (data.ultimosEventos.length === 0) {
        cont.innerHTML = "<h4>No hay enventos finalizados</h4>"
    }
    data.ultimosEventos.forEach(e => {
        cont.innerHTML += `         
          <div class="evento-row">
                <div>
                    <h4>${e.titulo}</h4>
                    <p>${e.fecha}</p>
                </div>
                <p><i class="bi bi-person"></i> 🪑 ${e.asientos_vendidos}</p>
                <span>$${Number(e.dinero).toLocaleString("es-CO")}</span>  
                 </div>         
        `;
    });
}
