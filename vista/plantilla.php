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
    "pago-exitoso" => "vista/Dashboards/Usuario/pago-exitoso.php",
    "mis_tickets" => "vista/Dashboards/Usuario/Mis_Tickets.php",
    "terminos_condiciones_cliente" => "vista/Dashboards/Usuario/Policies/Terminos_Condiciones.php",


];

// Rutas específicas para el rol de Empresa
$rutasEmpresa = [
    "dashboard-empresa" => "vista/Dashboards/Empresa/Dashboard_Empresa.php",
    "mi_perfil_empresa" => "vista/Dashboards/Empresa/Mi_Perfil_Empresa.php",
    "Configuracion_empresa" => "vista\Dashboards\Empresa\Configuraciones_Empresa.php",
    "analisis_corporativo" => "vista/Dashboards/Empresa/analisis_corporativo.php",
    "Reservar_funciones" => "vista/Dashboards/Empresa/Reservar_Funciones.php",
    "Eventos_realizados" => "vista\Dashboards\Empresa\Eventos_realizados.php",
];

// Rutas específicas para el rol de Administrador
$rutasAdmin = [
    //Dashboard 
    "dashboard-admin" => "vista/Dashboards/Administrador/Dashboard_Admin.php",
    "Ver_Empresas_Admin" => "vista/Dashboards/Administrador/Empresas/Ver_Empresas.php",
    "Ver_Usuarios_Admin" => "vista/Dashboards/Administrador/Usuarios/Ver_Usuarios.php",
    "Ver_Categorias_Admin" => "vista/Dashboards/Administrador/Categorias/Ver_Categorias.php",
    "Reservas" => "vista/Dashboards/Administrador/Resevas.php",
    "Reportes" => "vista/Dashboards/Administrador/Reportes.php",
    "estado_sistema" => "vista/Dashboards/Administrador/Estado_Sistema.php",
    //Perfil
    "mi_perfil_admin" => "vista\Dashboards\Administrador\Mi_Perfil.php",
    //Configuracion
    "Configuracion_admin" => "vista\Dashboards\Administrador\Settings\Configuracion.php",
    "cambiar_contrasena_admin" => "vista/Dashboards/Administrador/Settings/Cambiar_Contrasena_Admin.php",
    "cambiar_correo_admin" => "vista/Dashboards/Administrador/Settings/Cambiar_Correo_Admin.php",

    //Gestion Usuarios/Clientes
    "Crear_Usuario" => "vista/Dashboards/Administrador/Usuarios/Crear_Usuario.php",
    "Editar_Usuario" => "vista/Dashboards/Administrador/Usuarios/Editar_Usuario.php",

    //Gestion Empresas
    "Crear_Empresa" => "vista/Dashboards/Administrador/Empresas/Crear_Empresa.php",
    "Editar_Empresa" => "vista/Dashboards/Administrador/Empresas/Editar_Empresa.php",

    //Gestion Categorias
    "Crear_Categorias" => "vista/Dashboards/Administrador/Categorias/Crear_Categorias.php",
    "Editar_Categorias" => "vista/Dashboards/Administrador/Categorias/Editar_Categorias.php",
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
}

include_once "vista/modulos/zpie.php";
