<?php

include_once "vista/modulos/1cabesera.php";
include_once "controlador/usuarioControlador.php"; 


$rutasValidas = [
    "404" => "vista/modulos/404.php",
    "login" => "vista/modulos/login.php",
    "registro" => "vista/modulos/registro.php",
    "verificar_codigo" => "vista/modulos/Auth/verificar_codigo.php",
    "restablecer_contraseña" => "vista/modulos/Auth/restablecerContraseña.php",
    "recibir_correo" => "vista/modulos/Auth/recibir_correo.php",
    "mi_perfil" => "vista\Dashboards\Usuario\Mi_Perfil.php",
    "configuracion_cliente" => "vista/Dashboards/Usuario/Configuracion_Cliente.php",
    "cambiar_contrasena_cliente" => "vista/Dashboards/Usuario/Cambiar_Contrasena_Cliente.php",
    "cambiar_correo_cliente" => "vista/Dashboards/Usuario/Cambiar_Correo_Cliente.php",
    "seleccionar_asientos" => "vista/Dashboards/Usuario/Seleccionar_Asientos.php",
    "dashboard-usuario" => "vista/Dashboards/Usuario/Dashboard_Usuario.php",
    "dashboard-empresa" => "vista/Dashboards/Empresa/Dashboard_Empresa.php",
    "dashboard-admin" => "vista/Dashboards/Administrador/Dashboard_Admin.php"
];

$ruta = $_GET["ruta"] ?? "login";

$usuarioControlador = new UsuarioControlador();
$usuarioControlador->ctrIngresoUsuario();

if (array_key_exists($ruta, $rutasValidas)) {
    include_once $rutasValidas[$ruta];
} else {
    include_once $rutasValidas["404"];
}

include_once "vista/modulos/zpie.php";