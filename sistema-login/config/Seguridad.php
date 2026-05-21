<?php
// CLASE PARA MANEJAR LA SEGURIDAD DEL SISTEMA

class Seguridad {
    // ESTABLECE LAS CABECERAS DE SEGURIDAD HTTP
    public static function EstablecerCabecerasSeguridad() {
        header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self';");
        header("X-Frame-Options: DENY");
        header("X-Content-Type-Options: nosniff");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
    }

    // CONFIGURA LAS COOKIES DE SESION SEGURAS
    public static function IniciarSesionSegura() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => false, // CAMBIAR A TRUE SI SE USA HTTPS
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            session_start();
        }
    }

    // GENERA UN TOKEN CSRF Y LO GUARDA EN LA SESION
    public static function GenerarTokenCsrf() {
        if (empty($_SESSION['tokenCsrf'])) {
            $_SESSION['tokenCsrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['tokenCsrf'];
    }

    // VALIDA EL TOKEN CSRF RECIBIDO
    public static function ValidarTokenCsrf($tokenRecibido) {
        if (!isset($_SESSION['tokenCsrf']) || $tokenRecibido !== $_SESSION['tokenCsrf']) {
            return false;
        }
        return true;
    }

    // ESCAPA EL CONTENIDO PARA EVITAR XSS
    public static function EscaparHtml($datos) {
        return htmlspecialchars($datos, ENT_QUOTES, 'UTF-8');
    }
}
?>
