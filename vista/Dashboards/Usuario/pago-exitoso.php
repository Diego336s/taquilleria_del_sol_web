<?php
// Si los parámetros faltan, redirigir ANTES que el backend ejecute el "die()"
if (!isset($_GET["asientos"]) || !isset($_GET["cliente"]) || !isset($_GET["total"]) || !isset($_GET["evento"])) {
    header("Location: seleccion_asientos.php"); // cámbiala por donde quieras
    exit;
}
?>



<?php

// --- Leer parámetros ---
$asientosCod = $_GET["asientos"] ?? null;
$clienteCod  = $_GET["cliente"] ?? null;
$totalCod    = $_GET["total"] ?? null;
$eventoCod   = $_GET["evento"] ?? null;

// Validar
if (!$asientosCod || !$clienteCod || !$totalCod || !$eventoCod) {
    die("<h2>Error: Datos incompletos</h2>");
}

// --- Función para desencriptar ---
function decryptData($data)
{
    return json_decode(gzuncompress(base64_decode(strtr($data, '-_', '+/'))), true);
}

// Desencriptar valores
$asientos = decryptData($asientosCod);
$cliente  = decryptData($clienteCod);
$total    = decryptData($totalCod);
$evento   = decryptData($eventoCod);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pago Exitoso</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('vista/css/img/fondo.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
            margin: 0;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px; /* Añadimos un poco de padding por si el contenido es muy grande */
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                    rgba(0, 0, 0, 0.75) 0%,
                    rgba(0, 0, 0, 0.60) 50%,
                    rgba(0, 0, 0, 0.75) 100%);
            z-index: -1;
        }


        .card {
            /* --- Efecto Vidrio (Glassmorphism) --- */
            background: rgba(255, 255, 255, 0.15);
            /* Fondo blanco semitransparente */
            backdrop-filter: blur(10px);
            /* Efecto de desenfoque del fondo */
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* Borde sutil para definir el contorno */
            padding: 30px 40px;
            border-radius: 16px;
            max-width: 500px;
            margin: 0; /* Eliminamos el margen que ya no es necesario */
            display: inline-block;
            text-align: left;
        }

        h1 {
            color: #ffffffff;
            text-align: center;
            font-size: 2em;
            margin-bottom: 25px;
        }

        .info-section p {
            margin: 10px 0;
            line-height: 1.7;
            color: #e0e0e0;
        }

        .info-section strong,
        h3,
        li {
            color: white;
        }

        h3 {
            color: #ffffffff;
            margin-top: 25px;
            margin-bottom: 10px;
            border-top: 1px solid #333;
            padding-top: 25px;
        }

        ul {
            list-style-position: inside;
            padding: 0;
            margin-top: 10px;
        }

        li {
            margin-bottom: 8px;
        }

        .btn {
            background: #ff6b1f66;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin-top: 30px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            /* Para que el padding no afecte el ancho */
        }

        /* --- Loader tipo Stripe --- */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            /* Fondo propio (sólido o degradado), NO transparente */
            background: #2c2c2cff;
            /* gris clarito tipo Stripe */
            /* background: white;  si lo quieres blanco puro */

            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;

            z-index: 999999;
            transition: opacity 0.8s ease;
        }


        /* --- Estilos para el Loader --- */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 9999;
        }


        .loader-content p {
            color: white;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .spinner {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid #4CAF50;
            /* Color del spinner */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

</head>

<body>

    <div id="loader" class="loader-container">
        <div class="loader-content" style="text-align: center;">
            <div class="spinner"></div>
            <p>Terminando proceso de pago...</p>
        </div>
    </div>

    <div class="card" id="payment-card" style="display: none;">

        <h1>Pago Exitoso</h1>


        <div class="info-section">
            <p><strong>ID Cliente:</strong> <?= htmlspecialchars($cliente) ?></p>
            <p><strong>ID Evento:</strong> <?= htmlspecialchars($evento) ?></p>
            <p><strong>Total pagado:</strong> $<?= htmlspecialchars(number_format($total, 2)) ?></p>
        </div>

        <h3>Asientos comprados:</h3>
        <ul>
            <?php foreach ($asientos as $a): ?>
                <li>Asiento ID: <?= htmlspecialchars($a) ?></li>
            <?php endforeach; ?>
        </ul>

        <a class="btn" href="index.php?ruta=dashboard-usuario">Ir a Inicio</a>

    </div>

    <script src='../../js/ApiConexion.js'></script> <!-- URL base de la API -->


    <script>
        document.addEventListener("DOMContentLoaded", async () => {

            const params = new URLSearchParams(window.location.search);

            // URL al backend
            const backendUrl = `${ApiConexion}pago-exitoso-web?asientos=${params.get("asientos")}&cliente=${params.get("cliente")}&total=${params.get("total")}&evento=${params.get("evento")}`;

            console.log("URL enviada:", backendUrl);

            try {
                const res = await fetch(backendUrl);

                // Intentar leer como JSON si el servidor manda JSON
                try {
                    const data = await res.json();
                    console.log("Respuesta backend:", data);
                } catch (e) {
                    console.warn("Backend no devolvió JSON (ok para tu caso)");
                }

            } catch (error) {
                console.warn("Error en la llamada a la API:", error);
                // NO HACEMOS alert, porque lo quieres solo visual
            } finally {
                // Siempre ocultamos loader y mostramos la tarjeta
                document.getElementById("loader").style.display = "none";
                document.getElementById("payment-card").style.display = "inline-block";
            }
        });
    </script>


</body>

</html>