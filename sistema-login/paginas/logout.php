<?php
// CIERRE DE SESION SEGURO

require_once "../config/Seguridad.php";

Seguridad::IniciarSesionSegura();

// LIMPIAR TODAS LAS VARIABLES DE SESION
$_SESSION = array();

// SI SE DESEA DESTRUIR LA COOKIE DE SESION TAMBIEN
if (ini_get("session.use_cookies")) {
    $parametros = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $parametros["path"], $parametros["domain"],
        $parametros["secure"], $parametros["httponly"]
    );
}

// DESTRUIR LA SESION
session_destroy();

// REDIRIGIR AL LOGIN
header("Location: login.php");
exit();
?>
