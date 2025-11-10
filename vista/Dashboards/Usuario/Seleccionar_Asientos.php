<!-- Enlazamos la hoja de estilos principal para mantener la coherencia -->
<link rel="stylesheet" href="vista/css/main.css?v=1.1">
<style>
    .seat-selection-container {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 2rem;
        padding: 2rem;
        max-width: 1400px;
        margin: auto;
        min-height: 100vh;
    }
    .theatre-layout {
        background: var(--card-background);
        backdrop-filter: var(--card-blur);
        border: var(--card-border);
        border-radius: var(--border-radius);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .screen {
        background: #fff;
        color: #000;
        padding: 0.75rem 0;
        width: 70%;
        margin-bottom: 2rem;
        text-align: center;
        font-weight: bold;
        letter-spacing: 0.2rem;
        border-radius: 5px;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
    }
    .seat-row {
        display: flex;
        justify-content: center;
        margin-bottom: 0.75rem;
    }
    .seat {
        background-color: rgba(255, 255, 255, 0.2);
        height: 30px;
        width: 35px;
        margin: 0 5px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .seat.occupied {
        background-color: #555;
        cursor: not-allowed;
    }
    .seat.selected {
        background-color: #f97316; /* Naranja vibrante */
        transform: scale(1.1);
    }
    .seat:not(.occupied):hover {
        background-color: #f97316;
        opacity: 0.7;
    }
    .legend {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 2rem;
        list-style: none;
        padding: 0;
    }
    .legend li {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .legend .seat {
        height: 20px;
        width: 25px;
        cursor: default;
    }

    .booking-summary {
        background: var(--card-background);
        backdrop-filter: var(--card-blur);
        border: var(--card-border);
        border-radius: var(--border-radius);
        padding: 2rem;
        color: #fff;
    }
    .booking-summary h3 {
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding-bottom: 1rem;
    }
    #selected-seats-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
        max-height: 200px;
        overflow-y: auto;
    }
    #selected-seats-list li {
        padding: 0.5rem 0;
    }
    .total-price {
        font-size: 1.5rem;
        font-weight: bold;
        margin-top: 1rem;
    }

    @media (max-width: 992px) {
        .seat-selection-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="seat-selection-container">
    <!-- Columna Izquierda: Mapa del Teatro -->
    <div class="theatre-layout">
        <div class="text-center mb-4">
            <h1 class="h3 text-white">Romeo y Julieta</h1>
            <p class="text-white-50">15 Enero, 8:00 PM</p>
        </div>

        <div class="screen">ESCENARIO</div>

        <!-- Generación de asientos (ejemplo) -->
        <div class="seating-map">
            <div class="seat-row">
                <div class="seat" data-seat="A1"></div><div class="seat" data-seat="A2"></div><div class="seat occupied" data-seat="A3"></div><div class="seat" data-seat="A4"></div><div class="seat" data-seat="A5"></div><div class="seat" data-seat="A6"></div><div class="seat occupied" data-seat="A7"></div><div class="seat" data-seat="A8"></div>
            </div>
            <div class="seat-row">
                <div class="seat" data-seat="B1"></div><div class="seat" data-seat="B2"></div><div class="seat" data-seat="B3"></div><div class="seat occupied" data-seat="B4"></div><div class="seat occupied" data-seat="B5"></div><div class="seat" data-seat="B6"></div><div class="seat" data-seat="B7"></div><div class="seat" data-seat="B8"></div>
            </div>
            <div class="seat-row">
                <div class="seat" data-seat="C1"></div><div class="seat" data-seat="C2"></div><div class="seat" data-seat="C3"></div><div class="seat" data-seat="C4"></div><div class="seat" data-seat="C5"></div><div class="seat" data-seat="C6"></div><div class="seat" data-seat="C7"></div><div class="seat" data-seat="C8"></div>
            </div>
            <div class="seat-row">
                <div class="seat occupied" data-seat="D1"></div><div class="seat occupied" data-seat="D2"></div><div class="seat" data-seat="D3"></div><div class="seat" data-seat="D4"></div><div class="seat" data-seat="D5"></div><div class="seat" data-seat="D6"></div><div class="seat" data-seat="D7"></div><div class="seat" data-seat="D8"></div>
            </div>
            <div class="seat-row">
                <div class="seat" data-seat="E1"></div><div class="seat" data-seat="E2"></div><div class="seat" data-seat="E3"></div><div class="seat" data-seat="E4"></div><div class="seat occupied" data-seat="E5"></div><div class="seat occupied" data-seat="E6"></div><div class="seat" data-seat="E7"></div><div class="seat" data-seat="E8"></div>
            </div>
        </div>

        <ul class="legend">
            <li><div class="seat"></div> Disponible</li>
            <li><div class="seat selected"></div> Seleccionado</li>
            <li><div class="seat occupied"></div> Ocupado</li>
        </ul>
    </div>

    <!-- Columna Derecha: Resumen de la Reserva -->
    <div class="booking-summary">
        <h3>Resumen de tu Reserva</h3>
        <p>Asientos Seleccionados:</p>
        <ul id="selected-seats-list">
            <!-- Los asientos seleccionados aparecerán aquí -->
        </ul>
        <div class="total-price">
            Total: <span id="total-price">0</span> COP
        </div>
        <button class="btn btn-success w-100 py-2 mt-4">Confirmar Reserva</button>
         <p class="mt-4 text-center small">
            <a href="index.php?ruta=dashboard-usuario">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const seatingMap = document.querySelector('.seating-map');
    const selectedSeatsList = document.getElementById('selected-seats-list');
    const totalPriceEl = document.getElementById('total-price');
    const seatPrice = 45000; // Precio por asiento

    seatingMap.addEventListener('click', (e) => {
        if (e.target.classList.contains('seat') && !e.target.classList.contains('occupied')) {
            e.target.classList.toggle('selected');
            updateSummary();
        }
    });

    function updateSummary() {
        const selectedSeats = document.querySelectorAll('.seat.selected');
        
        // Limpiar lista
        selectedSeatsList.innerHTML = '';

        // Llenar lista y calcular precio
        let total = 0;
        selectedSeats.forEach(seat => {
            const seatNumber = seat.dataset.seat;
            const listItem = document.createElement('li');
            listItem.textContent = `Asiento ${seatNumber}`;
            selectedSeatsList.appendChild(listItem);
            total += seatPrice;
        });

        totalPriceEl.textContent = total.toLocaleString('es-CO');
    }
});
</script>