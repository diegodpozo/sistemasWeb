<?php
// PANEL DE CONTROL (DASHBOARD) TRAS EL LOGIN

require_once "config/Seguridad.php";

Seguridad::IniciarSesionSegura();
Seguridad::EstablecerCabecerasSeguridad();

// VERIFICAR SI EL USUARIO HA INICIADO SESION
if (!isset($_SESSION['usuarioId'])) {
    header("Location: paginas/login.php");
    exit();
}

$emailUsuario = $_SESSION['usuarioEmail'];

include "includes/encabezado.php";
?>

<h2>GESTION DE HORARIOS</h2>

<p>BIENVENIDO, <strong><?php echo Seguridad::EscaparHtml($emailUsuario); ?></strong>. INGRESA TUS MATERIAS:</p>

<form id="formulario-materias" class="grupo-formulario">
    <div class="grupo-formulario">
        <label for="materia">NOMBRE DE LA MATERIA:</label>
        <input type="text" id="materia" placeholder="EJ: PROGRAMACION I" required>
    </div>

    <div class="grupo-formulario">
        <label for="dia">DIA:</label>
        <select id="dia" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
            <option value="LUNES">LUNES</option>
            <option value="MARTES">MARTES</option>
            <option value="MIERCOLES">MIERCOLES</option>
            <option value="JUEVES">JUEVES</option>
            <option value="VIERNES">VIERNES</option>
        </select>
    </div>

    <div class="grupo-formulario">
        <label for="horario">HORARIO:</label>
        <input type="time" id="horario" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd;">
    </div>

    <button type="submit" class="boton-negro">AGREGAR MATERIA</button>
</form>

<div style="margin-top: 30px;">
    <h3>MATERIAS INGRESADAS</h3>
    <table id="tabla-materias" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
            <tr style="background-color: #000; color: #fff;">
                <th style="padding: 10px; border: 1px solid #ddd;">MATERIA</th>
                <th style="padding: 10px; border: 1px solid #ddd;">DIA</th>
                <th style="padding: 10px; border: 1px solid #ddd;">HORARIO</th>
            </tr>
        </thead>
        <tbody>
            <!-- LOS DATOS SE CARGARAN ACA CON JS -->
        </tbody>
    </table>
</div>

<div style="margin-top: 30px; text-align: center;">
    <a href="paginas/logout.php" class="enlace-secundario">CERRAR SESION</a>
</div>

<script>
document.getElementById('formulario-materias').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const materia = document.getElementById('materia').value;
    const dia = document.getElementById('dia').value;
    const horario = document.getElementById('horario').value;
    
    const tabla = document.getElementById('tabla-materias').getElementsByTagName('tbody')[0];
    const nuevaFila = tabla.insertRow();
    
    nuevaFila.innerHTML = `
        <td style="padding: 10px; border: 1px solid #ddd;">${materia}</td>
        <td style="padding: 10px; border: 1px solid #ddd;">${dia}</td>
        <td style="padding: 10px; border: 1px solid #ddd;">${horario} HS</td>
    `;
    
    this.reset();
});
</script><?php include "includes/pie.php"; ?>
