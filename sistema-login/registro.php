<?php
// CONTROLADOR Y VISTA PARA EL REGISTRO DE USUARIOS

require_once "config/BaseDeDatos.php";
require_once "config/Seguridad.php";
require_once "models/Usuario.php";

Seguridad::IniciarSesionSegura();
Seguridad::EstablecerCabecerasSeguridad();

$baseDatos = new BaseDeDatos();
$db = $baseDatos->ObtenerConexion();
$usuario = new Usuario($db);

$mensajeError = "";
$mensajeExito = "";

// PROCESA EL FORMULARIO SI SE RECIBE POR POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tokenRecibido = $_POST['tokenCsrf'] ?? '';
    
    if (Seguridad::ValidarTokenCsrf($tokenRecibido)) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $clave = $_POST['clave'] ?? '';

        if (!empty($email) && !empty($clave)) {
            if (!$usuario->EmailExiste($email)) {
                if ($usuario->Registrar($email, $clave)) {
                    $mensajeExito = "USUARIO REGISTRADO CON EXITO. YA PUEDE INICIAR SESION.";
                } else {
                    $mensajeError = "ERROR AL REGISTRAR EL USUARIO.";
                }
            } else {
                $mensajeError = "EL EMAIL YA ESTA REGISTRADO.";
            }
        } else {
            $mensajeError = "POR FAVOR COMPLETE TODOS LOS CAMPOS.";
        }
    } else {
        $mensajeError = "ERROR DE VALIDACION CSRF.";
    }
}

$tokenCsrf = Seguridad::GenerarTokenCsrf();

include "includes/encabezado.php";
?>

<h2>REGISTRO</h2>

<?php if ($mensajeError): ?>
    <div class="alerta alerta-error"><?php echo Seguridad::EscaparHtml($mensajeError); ?></div>
<?php endif; ?>

<?php if ($mensajeExito): ?>
    <div class="alerta alerta-exito"><?php echo Seguridad::EscaparHtml($mensajeExito); ?></div>
<?php endif; ?>

<form action="registro.php" method="POST">
    <input type="hidden" name="tokenCsrf" value="<?php echo $tokenCsrf; ?>">
    
    <div class="grupo-formulario">
        <label for="email">EMAIL:</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div class="grupo-formulario">
        <label for="clave">CONTRASEÑA:</label>
        <div class="contenedor-password">
            <input type="password" name="clave" id="clave" required>
            <button type="button" id="mostrar-clave" class="boton-ojo">MOSTRAR</button>
        </div>
        <div id="indicador-fortaleza" class="indicador-fortaleza"></div>
    </div>

    <button type="submit">REGISTRARSE</button>
</form>

<a href="login.php" class="enlace-secundario">¿YA TIENES CUENTA? INICIA SESION</a>

<?php include "includes/pie.php"; ?>
