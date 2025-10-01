<?php

class UsuarioControlador
{
    /**
     * Maneja el inicio de sesión del usuario.
     */
    public static function ctrIngresoUsuario()
    {
        if (isset($_POST["loginUser"])) {
            // Aquí iría la lógica para validar el usuario y la contraseña
            // contra la base de datos.
            // Por ejemplo:
            // $tabla = "usuarios";
            // $item = "email";
            // $valor = $_POST["loginUser"];
            // $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);
            // if($respuesta["email"] == $_POST["loginUser"] && $respuesta["password"] == $_POST["loginPassword"]){

            // Si la validación es exitosa:
            echo '<script>window.location = "index.php?ruta=dashboard-usuario";</script>';
            // }
        }
    }
}