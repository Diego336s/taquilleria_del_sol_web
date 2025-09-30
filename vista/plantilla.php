<?php

include_once "vista/modulos/1cabesera.php";

$rutasValidas = [
    "login" => "vista/modulos/login.php",
    "registro" => "vista/modulos/registro.php",
    "fogout_contraseña" => "vista/modulos/Auth/olvidarContraseña.php"
];

$ruta = $_GET["ruta"] ?? "login";

if (array_key_exists($ruta, $rutasValidas)) {
    include_once $rutasValidas[$ruta];
} else {

    include_once $rutasValidas["login"];
}

include_once "vista/modulos/zpie.php";