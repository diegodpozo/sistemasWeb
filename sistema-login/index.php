<?php
// PANEL DE CONTROL (DASHBOARD) TRAS EL LOGIN

require_once "config/Seguridad.php";

Seguridad::IniciarSesionSegura();
Seguridad::EstablecerCabecerasSeguridad();

// VERIFICAR SI EL USUARIO HA INICIADO SESION
if (!isset($_SESSION['usuarioId'])) {
    header("Location: login.php");
    exit();
}

$emailUsuario = $_SESSION['usuarioEmail'];

include "includes/encabezado.php";
?>

<h2>BIENVENIDO AL PANEL</h2>

<p>HOLA, <strong><?php echo Seguridad::EscaparHtml($emailUsuario); ?></strong>.</p>
<p>HAS INICIADO SESION DE FORMA SEGURA EN EL SISTEMA.</p>

<div style="margin-top: 30px; text-align: center;">
    <a href="logout.php" style="color: #dc3545; text-decoration: none; font-weight: bold;">CERRAR SESION</a>
</div>

<?php include "includes/pie.php"; ?>
