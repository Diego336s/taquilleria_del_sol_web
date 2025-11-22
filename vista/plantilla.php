<?php

include_once "vista/modulos/1cabesera.php";
include_once "controlador/usuarioControlador.php";

// --- Organización de Rutas por Rol ---

// Rutas públicas y comunes a todos
$rutasPublicasYComunes = [
    "login" => "vista/modulos/login.php",
    "registro" => "vista/modulos/registro.php",
    "verificar_codigo" => "vista/modulos/Auth/verificar_codigo.php",
    "restablecer_contraseña" => "vista/modulos/Auth/restablecerContraseña.php",
    "recibir_correo" => "vista/modulos/Auth/recibir_correo.php",
    "404" => "vista/modulos/404.php",
    "terminos_condiciones" => "vista/Dashboards/Usuario/Policies/Terminos_Condiciones.php",
];

// Rutas específicas para el rol de Cliente
$rutasCliente = [
    "dashboard-usuario" => "vista/Dashboards/Usuario/Dashboard_Usuario.php",
    "mi_perfil" => "vista\Dashboards\Usuario\Mi_Perfil.php",
    "configuracion_cliente" => "vista/Dashboards/Usuario/Settings/Configuracion_Cliente.php",
    "cambiar_contrasena_cliente" => "vista/Dashboards/Usuario/Settings/Cambiar_Contrasena_Cliente.php",
    "cambiar_correo_cliente" => "vista/Dashboards/Usuario/Settings/Cambiar_Correo_Cliente.php",
    "seleccionar_asientos" => "vista/Dashboards/Usuario/Seleccionar_Asientos.php",
    "pagar_reserva" => "vista/Dashboards/Usuario/Pagar_Reserva.php",

];

// Rutas específicas para el rol de Empresa
$rutasEmpresa = [
    "dashboard-empresa" => "vista/Dashboards/Empresa/Dashboard_Empresa.php",
    "mi_perfil_empresa" => "vista/Dashboards/Empresa/Mi_Perfil_Empresa.php",
    "Configuracion_empresa" => "vista\Dashboards\Empresa\Configuraciones_Empresa.php",
    "Reportes_empresa" => "vista/Dashboards/Empresa/Reportes_Empresa.php",
];

// Rutas específicas para el rol de Administrador
$rutasAdmin = [
    "dashboard-admin" => "vista/Dashboards/Administrador/Dashboard_Admin.php",
    "mi_perfil_admin" => "vista\Dashboards\Administrador\Mi_Perfil.php",
    "Configuracion_admin" => "vista\Dashboards\Administrador\Configuracion.php",
    "estado_sistema" => "vista/Dashboards/Administrador/Estado_Sistema.php",
    "reportes" => "vista/Dashboards/Administrador/Reportes.php",
    "Reservas" => "vista/Dashboards/Administrador/Resevas.php",
    "Reportes" => "vista/Dashboards/Administrador/Reportes.php",
    "Ver_Usuarios" => "vista/Dashboards/Administrador/Usuarios/Ver_Usuarios.php",
    "verEmpresas" => "vista/Dashboards/Administrador/Empresas/Ver_Empresas.php",
];

// Se unifican todas las rutas en un solo array para la validación
$rutasValidas = $rutasPublicasYComunes + $rutasCliente + $rutasEmpresa + $rutasAdmin;

// --- Inyección de Rutas para JavaScript ---
// Creamos un objeto JS con los nombres de las rutas para que Auth.js pueda usarlo.
$rutasParaJS = [
    'publicas' => array_keys($rutasPublicasYComunes),
    'protegidas' => [
        'cliente' => array_keys($rutasCliente),
        'empresa' => array_keys($rutasEmpresa),
        'admin' => array_keys($rutasAdmin)
    ]
];
echo "<script>window.APP_ROUTES = " . json_encode($rutasParaJS) . ";</script>";

// --- Lógica de Enrutamiento PHP ---

$ruta = $_GET["ruta"] ?? "login";

$usuarioControlador = new UsuarioControlador();
$usuarioControlador->ctrIngresoUsuario();

if (array_key_exists($ruta, $rutasValidas)) {
    include_once $rutasValidas[$ruta];
} else {
    include_once $rutasValidas["404"];
}

include_once "vista/modulos/zpie.php";
