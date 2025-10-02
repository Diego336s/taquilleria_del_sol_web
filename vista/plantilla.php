<?php

include_once "vista/modulos/1cabesera.php";
include_once "controlador/usuarioControlador.php"; 

$rutasValidas = [
    "login" => "vista/modulos/login.php",
    "registro" => "vista/modulos/registro.php",
    "fogout_contraseña" => "vista/modulos/Auth/olvidarContraseña.php",
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
    include_once $rutasValidas["login"];
}

include_once "vista/modulos/zpie.php";