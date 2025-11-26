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

    await cargarEntradasMensuales(empresa_id);
    await cargarTotalVendido(empresa_id);
    await cargarUltimosEventos(empresa_id);
});

async function cargarTotalVendido(empresaId) {
    const res = await fetch(ApiConexion + `total-vendido-empresa-año/${empresaId}`);
    const data = await res.json();

    console.log("TOTAL AÑO:", data);

    
        document.getElementById("contenedorIngresosTotales").innerHTML = `     
            <div class="progress-group">
                <p>Total vendido en el año:</p>
                <div class="bar">
                    <div class="fill" style="width:100%"></div>
                </div>
                
            </div>
            <h4>Total <span class="total">$${Number(data.total_vendido).toLocaleString("es-CO")}</span></h4>`;
    
}


async function cargarEntradasMensuales(empresaId) {
    const res = await fetch(ApiConexion + `entradas-mensuales/${empresaId}`);
    const data = await res.json();

    console.log("ENTRADAS MENSUALES:", data.estradasMensuales);
    const contenedor = document.getElementById("contenedorEntradasMensuales");
    let mesTexto = "";
    data.estradasMensuales.forEach(m => {
        switch (m.mes) {
            case 1:
                mesTexto = "Enero";
                break;
            case 2:
                mesTexto = "Febrero";
                break;
            case 3:
                mesTexto = "Marzo";
                break;
            case 4:
                mesTexto = "Abril";
                break;
            case 5:
                mesTexto = "Mayo";
                break;
            case 6:
                mesTexto = "Junio";
                break;
            case 7:
                mesTexto = "Julio";
                break;
            case 8:
                mesTexto = "Agosto";
                break;
            case 9:
                mesTexto = "Septiembre";
                break;
            case 10:
                mesTexto = "Octubre";
                break;
            case 11:
                mesTexto = "Noviembre";
                break;
            case 12:
                mesTexto = "Diciembre";
                break;

            default:
                mesTexto = "Mes no reconocido";
                break;
        }
        contenedor.innerHTML = `<p> ${mesTexto} <span>$${Number(m.total).toLocaleString("es-CO")}</span></p>
                <div class="bar">
                    <div class="fill" style="width:100%"></div>
                </div>`;

    });
}



async function cargarUltimosEventos(empresaId) {
    const res = await fetch(ApiConexion + `ultimos-eventos/${empresaId}`);
    const data = await res.json();

    console.log("ULTIMOS EVENTOS:", data.eventos);

    // Pintar en HTML
    const cont = document.getElementById("contenedorUltimosEventos");
    cont.innerHTML = "";

    data.ultimosEventos.forEach(e => {
        cont.innerHTML += `         
          <div class="evento-row">
                <div>
                    <h4>${e.titulo}</h4>
                    <p>${e.fecha}</p>
                </div>
                <p><i class="bi bi-person"></i> ${e.asientos_vendidos}</p>
                <span>$${Number(e.dinero).toLocaleString("es-CO")}</span>  
                 </div>         
        `;
    });
}
