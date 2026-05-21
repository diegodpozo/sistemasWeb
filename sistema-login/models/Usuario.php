<?php
// MODELO PARA GESTIONAR LOS USUARIOS Y EL ACCESO

class Usuario {
    private $conexion;
    private $nombreTabla = "Usuarios";

    public function __construct($db) {
        $this->conexion = $db;
    }

    // REGISTRA UN NUEVO USUARIO EN LA BASE DE DATOS
    public function Registrar($email, $clave) {
        $consultaSql = "INSERT INTO " . $this->nombreTabla . " (email, claveHash) VALUES (:email, :claveHash)";
        $sentencia = $this->conexion->prepare($consultaSql);

        $claveHash = password_hash($clave, PASSWORD_BCRYPT);

        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":claveHash", $claveHash);

        if ($sentencia->execute()) {
            return true;
        }
        return false;
    }

    // VERIFICA LAS CREDENCIALES DEL USUARIO
    public function IniciarSesion($email, $clave) {
        $consultaSql = "SELECT id, claveHash FROM " . $this->nombreTabla . " WHERE email = :email LIMIT 1";
        $sentencia = $this->conexion->prepare($consultaSql);
        $sentencia->bindParam(":email", $email);
        $sentencia->execute();

        if ($sentencia->rowCount() > 0) {
            $fila = $sentencia->fetch(PDO::FETCH_ASSOC);
            if (password_verify($clave, $fila['claveHash'])) {
                return $fila['id'];
            }
        }
        return false;
    }

    // COMPRUEBA SI EL EMAIL YA ESTA REGISTRADO
    public function EmailExiste($email) {
        $consultaSql = "SELECT id FROM " . $this->nombreTabla . " WHERE email = :email LIMIT 1";
        $sentencia = $this->conexion->prepare($consultaSql);
        $sentencia->bindParam(":email", $email);
        $sentencia->execute();

        return $sentencia->rowCount() > 0;
    }

    // REGISTRA UN INTENTO FALLIDO DE INICIO DE SESION
    public function RegistrarIntentoFallido($email, $direccionIp) {
        $consultaSql = "INSERT INTO IntentosLogin (email, direccionIp) VALUES (:email, :direccionIp)";
        $sentencia = $this->conexion->prepare($consultaSql);
        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":direccionIp", $direccionIp);
        $sentencia->execute();
    }

    // VERIFICA SI EL USUARIO ESTA BLOQUEADO POR DEMASIADOS INTENTOS
    public function EstaBloqueado($email) {
        $consultaSql = "SELECT COUNT(*) FROM IntentosLogin 
                        WHERE email = :email 
                        AND fechaIntento > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
        $sentencia = $this->conexion->prepare($consultaSql);
        $sentencia->bindParam(":email", $email);
        $sentencia->execute();

        $numeroIntentos = $sentencia->fetchColumn();
        return $numeroIntentos >= 5;
    }

    // ELIMINA LOS INTENTOS FALLIDOS TRAS UN ACCESO EXITOSO
    public function LimpiarIntentos($email) {
        $consultaSql = "DELETE FROM IntentosLogin WHERE email = :email";
        $sentencia = $this->conexion->prepare($consultaSql);
        $sentencia->bindParam(":email", $email);
        $sentencia->execute();
    }
}
?>
