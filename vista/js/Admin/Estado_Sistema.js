// Estado del Sistema - JavaScript para funcionalidad dinámica
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startRealTimeUpdates();
    loadSystemMetrics();
});

// Inicializar gráficos con Chart.js
function initializeCharts() {
    // Gráfico de Actividad de Usuarios
    const userActivityCtx = document.getElementById('userActivityChart').getContext('2d');
    new Chart(userActivityCtx, {
        type: 'line',
        data: {
            labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
            datasets: [{
                label: 'Usuarios Activos',
                data: [45, 89, 234, 456, 387, 298],
                borderColor: '#ff6b1f',
                backgroundColor: 'rgba(255, 107, 31, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: '#cccccc'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#cccccc'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Gráfico de Recursos del Servidor
    const serverResourcesCtx = document.getElementById('serverResourcesChart').getContext('2d');
    new Chart(serverResourcesCtx, {
        type: 'doughnut',
        data: {
            labels: ['CPU', 'Memoria', 'Disco', 'Red'],
            datasets: [{
                data: [23, 45, 67, 12],
                backgroundColor: [
                    '#ff6b1f',
                    '#28a745',
                    '#007bff',
                    '#ffc107'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            }
        }
    });

    // Gráfico de Ventas por Mes
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Ventas ($)',
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                backgroundColor: '#ff6b1f',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        color: '#cccccc',
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#cccccc'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Gráfico de Distribución de Eventos
    const eventsCtx = document.getElementById('eventsChart').getContext('2d');
    new Chart(eventsCtx, {
        type: 'pie',
        data: {
            labels: ['Teatro', 'Conciertos', 'Deportes', 'Otros'],
            datasets: [{
                data: [35, 25, 20, 20],
                backgroundColor: [
                    '#ff6b1f',
                    '#28a745',
                    '#007bff',
                    '#ffc107'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#ffffff'
                    }
                }
            }
        }
    });
}

// Actualizaciones en tiempo real
function startRealTimeUpdates() {
    setInterval(() => {
        updateSystemMetrics();
        updateActivityFeed();
    }, 30000); // Actualizar cada 30 segundos
}

// Cargar métricas del sistema
function loadSystemMetrics() {
    // Simular carga de datos del servidor
    fetch('api/system/metrics')
        .then(response => response.json())
        .then(data => {
            updateMetricsDisplay(data);
        })
        .catch(error => {
            console.log('Usando datos simulados');
            updateMetricsDisplay(getMockData());
        });
}

// Actualizar métricas en la interfaz
function updateMetricsDisplay(data) {
    document.getElementById('activeUsers').textContent = data.activeUsers.toLocaleString();
    document.getElementById('companies').textContent = data.companies;
    document.getElementById('ticketsSold').textContent = data.ticketsSold.toLocaleString();
    document.getElementById('systemLoad').textContent = data.systemLoad + '%';

    // Actualizar indicadores de estado
    updateStatusIndicators(data.services);
}

// Actualizar indicadores de estado
function updateStatusIndicators(services) {
    const statusElements = document.querySelectorAll('.status-dot');
    statusElements.forEach((element, index) => {
        const service = services[index];
        element.className = 'status-dot status-' + service.status;
        element.nextElementSibling.textContent = service.statusText;
    });
}

// Actualizar feed de actividad
function updateActivityFeed() {
    const activityList = document.getElementById('activityList');
    const newActivity = createActivityItem(getRandomActivity());

    // Agregar al inicio de la lista
    activityList.insertBefore(newActivity, activityList.firstChild);

    // Mantener solo las últimas 10 actividades
    while (activityList.children.length > 10) {
        activityList.removeChild(activityList.lastChild);
    }
}

// Crear elemento de actividad
function createActivityItem(activity) {
    const item = document.createElement('div');
    item.className = 'activity-item';
    item.innerHTML = `
        <div class="activity-icon ${activity.type}">
            <i class="${activity.icon}"></i>
        </div>
        <div class="activity-content">
            <div class="activity-title">${activity.title}</div>
            <div class="activity-time">${activity.time}</div>
        </div>
    `;
    return item;
}

// Función de actualización manual
function refreshSystemStatus() {
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="icon-inline">⏳</span> Actualizando...';
    btn.disabled = true;

    setTimeout(() => {
        loadSystemMetrics();
        updateActivityFeed();
        btn.innerHTML = originalText;
        btn.disabled = false;

        // Mostrar notificación de éxito
        showNotification('Estado del sistema actualizado', 'success');
    }, 2000);
}

// Cargar más actividad
function loadMoreActivity() {
    // Simular carga de más actividades
    for (let i = 0; i < 5; i++) {
        setTimeout(() => {
            updateActivityFeed();
        }, i * 200);
    }
}

// Mostrar notificaciones
function showNotification(message, type) {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;

    // Agregar al DOM
    document.body.appendChild(notification);

    // Animar entrada
    setTimeout(() => notification.classList.add('show'), 100);

    // Remover después de 3 segundos
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => document.body.removeChild(notification), 300);
    }, 3000);
}

// Datos simulados para desarrollo
function getMockData() {
    return {
        activeUsers: 1245,
        companies: 42,
        ticketsSold: 8945,
        systemLoad: 23,
        services: [
            { status: 'active', statusText: 'Activo' },
            { status: 'active', statusText: 'Activo' },
            { status: 'active', statusText: 'Activo' },
            { status: 'active', statusText: 'Protegiendo' }
        ]
    };
}

// Generar actividad aleatoria
function getRandomActivity() {
    const activities = [
        {
            type: 'success',
            icon: 'fas fa-check-circle',
            title: 'Backup completado exitosamente',
            time: 'Ahora mismo'
        },
        {
            type: 'info',
            icon: 'fas fa-user-plus',
            title: 'Nuevo usuario registrado',
            time: 'Hace 2 minutos'
        },
        {
            type: 'warning',
            icon: 'fas fa-exclamation-triangle',
            title: 'Alerta de rendimiento',
            time: 'Hace 5 minutos'
        },
        {
            type: 'success',
            icon: 'fas fa-dollar-sign',
            title: 'Pago procesado',
            time: 'Hace 8 minutos'
        }
    ];

    return activities[Math.floor(Math.random() * activities.length)];
}

// Actualizar métricas del sistema periódicamente
function updateSystemMetrics() {
    // Simular actualización de métricas
    const mockData = getMockData();
    // Agregar variación aleatoria
    mockData.activeUsers += Math.floor(Math.random() * 20) - 10;
    mockData.systemLoad += Math.floor(Math.random() * 10) - 5;

    updateMetricsDisplay(mockData);
}
