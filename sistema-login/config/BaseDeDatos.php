<?php
// CONFIGURACION DE LA CONEXION A LA BASE DE DATOS

class BaseDeDatos {
    private $servidor = "localhost";
    private $nombreBaseDatos = "login_seguro";
    private $usuario = "root";
    private $clave = "";
    public $conexion;

    // OBTIENE LA CONEXION A LA BASE DE DATOS USANDO PDO
    public function ObtenerConexion() {
        $this->conexion = null;

        try {
            $this->conexion = new PDO(
                "mysql:host=" . $this->servidor . ";dbname=" . $this->nombreBaseDatos,
                $this->usuario,
                $this->clave
            );
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->exec("set names utf8");
        } catch (PDOException $excepcion) {
            echo "ERROR DE CONEXION: " . $excepcion->getMessage();
        }

        return $this->conexion;
    }
}
?>
