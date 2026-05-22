<?php
// CONTROLADOR Y VISTA PARA EL INICIO DE SESION

require_once "config/BaseDeDatos.php";
require_once "config/Seguridad.php";
require_once "models/Usuario.php";

Seguridad::IniciarSesionSegura();
Seguridad::EstablecerCabecerasSeguridad();

// REDIRIGIR SI YA ESTA LOGUEADO
if (isset($_SESSION['usuarioId'])) {
    header("Location: index.php");
    exit();
}

$baseDatos = new BaseDeDatos();
$db = $baseDatos->ObtenerConexion();
$usuario = new Usuario($db);

$mensajeError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tokenRecibido = $_POST['tokenCsrf'] ?? '';

    if (Seguridad::ValidarTokenCsrf($tokenRecibido)) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $clave = $_POST['clave'] ?? '';
        $direccionIp = $_SERVER['REMOTE_ADDR'];

        if (!empty($email) && !empty($clave)) {
            // VERIFICAR BLOQUEO POR INTENTOS FALLIDOS
            if (!$usuario->EstaBloqueado($email)) {
                $idUsuario = $usuario->IniciarSesion($email, $clave);

                if ($idUsuario) {
                    // LOGIN EXITOSO
                    session_regenerate_id(true); // PROTECCION CONTRA SESSION HIJACKING
                    $_SESSION['usuarioId'] = $idUsuario;
                    $_SESSION['usuarioEmail'] = $email;
                    $usuario->LimpiarIntentos($email);
                    header("Location: index.php");
                    exit();
                } else {
                    // LOGIN FALLIDO
                    $usuario->RegistrarIntentoFallido($email, $direccionIp);
                    $mensajeError = "CREDENCIALES INCORRECTAS."; // MENSAJE GENERICO POR SEGURIDAD
                }
            } else {
                $mensajeError = "CUENTA BLOQUEADA TEMPORALMENTE POR DEMASIADOS INTENTOS. ESPERE 15 MINUTOS.";
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

<h2>INICIO DE SESION</h2>

<?php if ($mensajeError): ?>
    <div class="alerta alerta-error"><?php echo Seguridad::EscaparHtml($mensajeError); ?></div>
<?php endif; ?>

<form action="login.php" method="POST">
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
    </div>

    <button type="submit">INGRESAR</button>
</form>

<a href="registro.php" class="enlace-secundario">¿NO TIENES CUENTA? REGISTRATE</a>

<?php include "includes/pie.php"; ?>
